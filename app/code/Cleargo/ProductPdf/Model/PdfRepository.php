<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\ProductPdf\Model;

use Cleargo\ProductPdf\Api\Data;
use Cleargo\ProductPdf\Api\PdfRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException; 
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Cleargo\ProductPdf\Model\ResourceModel\Pdf as ResourcePdf;
use Cleargo\ProductPdf\Model\ResourceModel\Pdf\CollectionFactory as PdfCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class PdfRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PdfRepository implements PdfRepositoryInterface
{
    /**
     * @var ResourcePdf
     */
    protected $resource;

    /**
     * @var PdfFactory
     */
    protected $pdfFactory;

    /**
     * @var PdfCollectionFactory
     */
    protected $pdfCollectionFactory;

    /**
     * @var Data\PdfSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var \Cleargo\ProductPdf\Api\Data\PdfInterfaceFactory
     */
    protected $dataPdfFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param ResourcePdf $resource
     * @param PdfFactory $pdfFactory
     * @param Data\PdfInterfaceFactory $dataPdfFactory
     * @param PdfCollectionFactory $pdfCollectionFactory
     * @param Data\PdfSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourcePdf $resource,
        PdfFactory $pdfFactory,
        \Cleargo\ProductPdf\Api\Data\PdfInterfaceFactory $dataPdfFactory,
        PdfCollectionFactory $pdfCollectionFactory,
        Data\PdfSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->pdfFactory = $pdfFactory;
        $this->pdfCollectionFactory = $pdfCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataPdfFactory = $dataPdfFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * Save Pdf data
     *
     * @param \Cleargo\ProductPdf\Api\Data\PdfInterface $pdf
     * @return Pdf
     * @throws CouldNotSaveException
     */
    public function save(Data\PdfInterface $pdf)
    {
       
        $storeId = $this->storeManager->getStore()->getId();
        $pdf->setStoreId($storeId);
        try {
            $this->resource->save($pdf);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $pdf;
    }

    /**
     * Load Pdf data by given Pdf Identity
     *
     * @param string $pdfId
     * @return Pdf
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($pdfId)
    {
        $pdf = $this->pdfFactory->create();
        $this->resource->load($pdf, $pdfId);
        if (!$pdf->getId()) {
            //throw new NoSuchEntityException(__('CMS Pdf with id "%1" does not exist.', $pdfId));
        }
        return $pdf;
    }
    public function getByProductId($productId,$storeId)
    {
        /*$pdf = $this->pdfFactory->create();
        $this->resource->load($pdf, $productId , 'linked_product_id' );
        //var_dump($pdf->getId());
        if (!$pdf->getId()) {
            //throw new NoSuchEntityException(__('CMS Pdf with id "%1" does not exist.', $pdfId));
        }
        return $pdf;*/
        //$searchResults = $this->searchResultsFactory->create();

        $collection = $this->pdfCollectionFactory->create();
        $collection->addStoreFilter($storeId)->addFieldToFilter('linked_product_id',$productId);
        $pdfs = $collection->getData();
        if(empty($pdfs)){
            return $pdfs;
        } else {
            return is_array($pdfs)? $pdfs[0] : $pdfs;
        }

    }

    /**
     * Load Pdf data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Cleargo\ProductPdf\Model\ResourceModel\Pdf\Collection
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->pdfCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $pdfs = [];
        /** @var Pdf $pdfModel */
        foreach ($collection as $pdfModel) {
            $pdfData = $this->dataPdfFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $pdfData,
                $pdfModel->getData(),
                'Cleargo\ProductPdf\Api\Data\PdfInterface'
            );
            $pdfs[] = $this->dataObjectProcessor->buildOutputDataArray(
                $pdfData,
                'Cleargo\ProductPdf\Api\Data\PdfInterface'
            );
        }
        $searchResults->setItems($pdfs);
        return $searchResults;
    }

    /**
     * Delete Pdf
     *
     * @param \Cleargo\ProductPdf\Api\Data\PdfInterface $pdf
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Data\PdfInterface $pdf)
    {
        try {
            $this->resource->delete($pdf);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Pdf by given Pdf Identity
     *
     * @param string $pdfId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($pdfId)
    {
        return $this->delete($this->getById($pdfId));
    }
}
