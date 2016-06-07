<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Model;

use Cleargo\DealerNetwork\Api\Data;
use Cleargo\DealerNetwork\Api\RegionRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Cleargo\DealerNetwork\Model\ResourceModel\Region as ResourceRegion;
use Cleargo\DealerNetwork\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class RegionRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class RegionRepository implements RegionRepositoryInterface
{
    /**
     * @var ResourceRegion
     */
    protected $resource;

    /**
     * @var RegionFactory
     */
    protected $regionFactory;

    /**
     * @var RegionCollectionFactory
     */
    protected $regionCollectionFactory;

    /**
     * @var Data\RegionSearchResultsInterfaceFactory
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
     * @var \Cleargo\DealerNetwork\Api\Data\RegionInterfaceFactory
     */
    protected $dataRegionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param ResourceRegion $resource
     * @param RegionFactory $regionFactory
     * @param Data\RegionInterfaceFactory $dataRegionFactory
     * @param RegionCollectionFactory $regionCollectionFactory
     * @param Data\RegionSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceRegion $resource,
        RegionFactory $regionFactory,
        Data\RegionInterfaceFactory $dataRegionFactory,
        RegionCollectionFactory $regionCollectionFactory,
        Data\RegionSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->regionFactory = $regionFactory;
        $this->regionCollectionFactory = $regionCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataRegionFactory = $dataRegionFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * Save Region data
     *
     * @param \Cleargo\DealerNetwork\Api\Data\RegionInterface $region
     * @return Region
     * @throws CouldNotSaveException
     */
    public function save(\Cleargo\DealerNetwork\Api\Data\RegionInterface $region)
    {
        $storeId = $this->storeManager->getStore()->getId();
        $region->setStoreId($storeId);
        try {
            $this->resource->save($region);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $region;
    }

    /**
     * Load Region data by given Region Identity
     *
     * @param string $regionId
     * @return Region
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($regionId)
    {
        $region = $this->regionFactory->create();
        $region->load($regionId);
        if (!$region->getId()) {
            throw new NoSuchEntityException(__('Dealer Region with id "%1" does not exist.', $regionId));
        }
        return $region;
    }

    /**
     * Load Region data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Cleargo\DealerNetwork\Model\ResourceModel\Region\Collection
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->regionCollectionFactory->create();
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
        $regions = [];
        /** @var Region $regionModel */
        foreach ($collection as $regionModel) {
            $regions[] = $regionModel;
        }
        $searchResults->setItems($regions);
        return $searchResults;
    }

    /**
     * Delete Region
     *
     * @param \Cleargo\DealerNetwork\Api\Data\RegionInterface $region
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(\Cleargo\DealerNetwork\Api\Data\RegionInterface $region)
    {
        try {
            $this->resource->delete($region);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Region by given Region Identity
     *
     * @param string $regionId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($regionId)
    {
        return $this->delete($this->getById($regionId));
    }
}
