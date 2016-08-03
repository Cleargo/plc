<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\PopupForm\Model;

use Cleargo\PopupForm\Api\Data;
use Cleargo\PopupForm\Api\InquiryRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException; 
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Cleargo\PopupForm\Model\ResourceModel\Inquiry as ResourceInquiry;
use Cleargo\PopupForm\Model\ResourceModel\Inquiry\CollectionFactory as InquiryCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class InquiryRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InquiryRepository implements InquiryRepositoryInterface
{
    /**
     * @var ResourceInquiry
     */
    protected $resource;

    /**
     * @var InquiryFactory
     */
    protected $pdfFactory;

    /**
     * @var InquiryCollectionFactory
     */
    protected $pdfCollectionFactory;

    /**
     * @var Data\InquirySearchResultsInterfaceFactory
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
     * @var \Cleargo\PopupForm\Api\Data\InquiryInterfaceFactory
     */
    protected $dataInquiryFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param ResourceInquiry $resource
     * @param InquiryFactory $pdfFactory
     * @param Data\InquiryInterfaceFactory $dataInquiryFactory
     * @param InquiryCollectionFactory $pdfCollectionFactory
     * @param Data\InquirySearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceInquiry $resource,
        InquiryFactory $pdfFactory,
        Data\InquiryInterfaceFactory $dataInquiryFactory,
        InquiryCollectionFactory $pdfCollectionFactory,
        Data\InquirySearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->pdfFactory = $pdfFactory;
        $this->pdfCollectionFactory = $pdfCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataInquiryFactory = $dataInquiryFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * Save Inquiry data
     *
     * @param \Cleargo\PopupForm\Api\Data\InquiryInterface $pdf
     * @return Inquiry
     * @throws CouldNotSaveException
     */
    public function save(Data\InquiryInterface $pdf)
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
     * Load Inquiry data by given Inquiry Identity
     *
     * @param string $pdfId
     * @return Inquiry
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($pdfId)
    {
        $pdf = $this->pdfFactory->create();
        $this->resource->load($pdf, $pdfId);
        if (!$pdf->getId()) {
            //throw new NoSuchEntityException(__('CMS Inquiry with id "%1" does not exist.', $pdfId));
        }
        return $pdf;
    }

    /**
     * Load Inquiry data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Cleargo\PopupForm\Model\ResourceModel\Inquiry\Collection
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
        /** @var Inquiry $pdfModel */
        foreach ($collection as $pdfModel) {
            $pdfData = $this->dataInquiryFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $pdfData,
                $pdfModel->getData(),
                'Cleargo\PopupForm\Api\Data\InquiryInterface'
            );
            $pdfs[] = $this->dataObjectProcessor->buildOutputDataArray(
                $pdfData,
                'Cleargo\PopupForm\Api\Data\InquiryInterface'
            );
        }
        $searchResults->setItems($pdfs);
        return $searchResults;
    }

    /**
     * Delete Inquiry
     *
     * @param \Cleargo\PopupForm\Api\Data\InquiryInterface $pdf
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Data\InquiryInterface $pdf)
    {
        try {
            $this->resource->delete($pdf);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Inquiry by given Inquiry Identity
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
