<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\Warranty\Model;

use Cleargo\Warranty\Api\Data;
use Cleargo\Warranty\Api\WarrantyRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Cleargo\Warranty\Model\ResourceModel\Warranty as ResourceWarranty;
use Cleargo\Warranty\Model\ResourceModel\Warranty\CollectionFactory as WarrantyCollectionFactory;

/**
 * Class WarrantyRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class WarrantyRepository implements WarrantyRepositoryInterface
{
    /**
     * @var ResourceWarranty
     */
    protected $resource;

    /**
     * @var WarrantyFactory
     */
    protected $warrantyFactory;

    /**
     * @var WarrantyCollectionFactory
     */
    protected $warrantyCollectionFactory;

    /**
     * @var Data\WarrantySearchResultsInterfaceFactory
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
     * @var \Cleargo\Warranty\Api\Data\WarrantyInterfaceFactory
     */
    protected $dataWarrantyFactory;


    /**
     * @param ResourceWarranty $resource
     * @param WarrantyFactory $warrantyFactory
     * @param Data\WarrantyInterfaceFactory $dataWarrantyFactory
     * @param WarrantyCollectionFactory $warrantyCollectionFactory
     * @param Data\WarrantySearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceWarranty $resource,
        WarrantyFactory $warrantyFactory,
        Data\WarrantyInterfaceFactory $dataWarrantyFactory,
        WarrantyCollectionFactory $warrantyCollectionFactory,
        Data\WarrantySearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor
    ) {
        $this->resource = $resource;
        $this->warrantyFactory = $warrantyFactory;
        $this->warrantyCollectionFactory = $warrantyCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataWarrantyFactory = $dataWarrantyFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    /**
     * Save Warranty data
     *
     * @param \Cleargo\Warranty\Api\Data\WarrantyInterface $warranty
     * @return Warranty
     * @throws CouldNotSaveException
     */
    public function save(\Cleargo\Warranty\Api\Data\WarrantyInterface $warranty)
    {
        try {
            $this->resource->save($warranty);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $warranty;
    }

    /**
     * Load Warranty data by given Warranty Identity
     *
     * @param string $warrantyId
     * @return Warranty
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($warrantyId)
    {
        $warranty = $this->warrantyFactory->create();
        $warranty->load($warrantyId);
        if (!$warranty->getId()) {
            throw new NoSuchEntityException(__('District Warranty with id "%1" does not exist.', $warrantyId));
        }
        return $warranty;
    }

    /**
     * Load Warranty data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Cleargo\Warranty\Model\ResourceModel\Warranty\Collection
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->warrantyCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $warrantys = [];
        /** @var Warranty $warrantyModel */
        foreach ($collection as $warrantyModel) {
            $warrantys[] = $warrantyModel;
        }
        $searchResults->setItems($warrantys);
        return $searchResults;
    }

    /**
     * Delete Warranty
     *
     * @param \Cleargo\Warranty\Api\Data\WarrantyInterface $warranty
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(\Cleargo\Warranty\Api\Data\WarrantyInterface $warranty)
    {
        try {
            $this->resource->delete($warranty);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Warranty by given Warranty Identity
     *
     * @param string $warrantyId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($warrantyId)
    {
        return $this->delete($this->getById($warrantyId));
    }
}
