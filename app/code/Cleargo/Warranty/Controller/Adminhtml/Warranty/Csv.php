<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\Warranty\Controller\Adminhtml\Warranty;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Model\Export\ConvertToCsv;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
/**
 * Adminhtml dealer blocks content block
 */
class Csv extends Action
{
    /**
     * @var WriteInterface
     */
    protected $directory;
    /**
     * @return void
     */
    protected $converter;

    /**
     * @var FileFactory
     */
    protected $fileFactory;
    protected $warrantyRepository;
    protected $searchCriteriaBuilder;
    protected $resourceConnection;

    /**
     * @param Context $context
     * @param ConvertToCsv $converter
     * @param FileFactory $fileFactory
     */
    public function __construct(
        Context $context,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ConvertToCsv $converter,
        \Magento\Framework\Filesystem $filesystem,
        \Cleargo\Warranty\Model\WarrantyRepository $warrantyRepository,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        FileFactory $fileFactory
    ) {

        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->converter = $converter;
        $this->warrantyRepository = $warrantyRepository;
        $this->resourceConnection = $resourceConnection;
        $this->directory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $this->fileFactory = $fileFactory;
        parent::__construct($context);
    }

    /**
     * Export data provider to CSV
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        return $this->fileFactory->create('export.csv', $this->getCsvFile(), 'var');
    }

    protected function getCsvFile()
    {
        $component = $this->warrantyRepository->getList($this->searchCriteriaBuilder->create())->getItems();

        $name = md5(microtime());
        $file = 'export/'. $component->getName() . $name . '.csv';

        $this->filter->prepareComponent($component);
        $this->filter->applySelectionOnTargetProvider();

        $searchResult = $component->getContext()->getDataProvider()->getSearchResult();
        $fields = $this->metadataProvider->getFields($component);
        $options = $this->metadataProvider->getOptions();

        $this->directory->create('export');
        $stream = $this->directory->openFile($file, 'w+');
        $stream->lock();
        $stream->writeCsv($this->metadataProvider->getHeaders($component));
        foreach ($searchResult->getItems() as $document) {
            $this->metadataProvider->convertDate($document, $component->getName());
            $stream->writeCsv($this->metadataProvider->getRowData($document, $fields, $options));
        }
        $stream->unlock();
        $stream->close();

        return [
            'type' => 'filename',
            'value' => $file,
            'rm' => true  // can delete file after use
        ];
    }
}
