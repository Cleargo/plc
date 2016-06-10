<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Model;

use Cleargo\FindYourLock\Api\Data;
use Cleargo\FindYourLock\Api\DistrictRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Cleargo\FindYourLock\Model\ResourceModel\District as ResourceDistrict;
use Cleargo\FindYourLock\Model\ResourceModel\District\CollectionFactory as DistrictCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class DistrictRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class DistrictRepository implements DistrictRepositoryInterface
{
    /**
     * @var ResourceDistrict
     */
    protected $resource;

    /**
     * @var DistrictFactory
     */
    protected $districtFactory;

    /**
     * @var DistrictCollectionFactory
     */
    protected $districtCollectionFactory;

    /**
     * @var Data\DistrictSearchResultsInterfaceFactory
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
     * @var \Cleargo\FindYourLock\Api\Data\DistrictInterfaceFactory
     */
    protected $dataDistrictFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param ResourceDistrict $resource
     * @param DistrictFactory $districtFactory
     * @param Data\DistrictInterfaceFactory $dataDistrictFactory
     * @param DistrictCollectionFactory $districtCollectionFactory
     * @param Data\DistrictSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceDistrict $resource,
        DistrictFactory $districtFactory,
        Data\DistrictInterfaceFactory $dataDistrictFactory,
        DistrictCollectionFactory $districtCollectionFactory,
        Data\DistrictSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->districtFactory = $districtFactory;
        $this->districtCollectionFactory = $districtCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataDistrictFactory = $dataDistrictFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * Save District data
     *
     * @param \Cleargo\FindYourLock\Api\Data\DistrictInterface $district
     * @return District
     * @throws CouldNotSaveException
     */
    public function save(\Cleargo\FindYourLock\Api\Data\DistrictInterface $district)
    {
        $storeId = $this->storeManager->getStore()->getId();
        $district->setStoreId($storeId);
        try {
            $this->resource->save($district);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $district;
    }

    /**
     * Load District data by given District Identity
     *
     * @param string $districtId
     * @return District
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($districtId)
    {
        $district = $this->districtFactory->create();
        $district->load($districtId);
        if (!$district->getId()) {
            throw new NoSuchEntityException(__('District District with id "%1" does not exist.', $districtId));
        }
        return $district;
    }

    /**
     * Load District data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Cleargo\FindYourLock\Model\ResourceModel\District\Collection
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->districtCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), true);
                    continue;
                }
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
        $districts = [];
        /** @var District $districtModel */
        foreach ($collection as $districtModel) {
            $districts[] = $districtModel;
        }
        $searchResults->setItems($districts);
        return $searchResults;
    }

    /**
     * Delete District
     *
     * @param \Cleargo\FindYourLock\Api\Data\DistrictInterface $district
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(\Cleargo\FindYourLock\Api\Data\DistrictInterface $district)
    {
        try {
            $this->resource->delete($district);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete District by given District Identity
     *
     * @param string $districtId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($districtId)
    {
        return $this->delete($this->getById($districtId));
    }
}
