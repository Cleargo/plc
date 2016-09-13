<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Model;

use Cleargo\FindYourLock\Api\Data;
use Cleargo\FindYourLock\Api\LockRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Cleargo\FindYourLock\Model\ResourceModel\Lock as ResourceLock;
use Cleargo\FindYourLock\Model\ResourceModel\Lock\CollectionFactory as LockCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class LockRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class LockRepository implements LockRepositoryInterface
{
    /**
     * @var ResourceLock
     */
    protected $resource;

    /**
     * @var LockFactory
     */
    protected $lockFactory;

    /**
     * @var LockCollectionFactory
     */
    protected $lockCollectionFactory;

    /**
     * @var Data\LockSearchResultsInterfaceFactory
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
     * @var \Cleargo\FindYourLock\Api\Data\LockInterfaceFactory
     */
    protected $dataLockFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param ResourceLock $resource
     * @param LockFactory $lockFactory
     * @param Data\LockInterfaceFactory $dataLockFactory
     * @param LockCollectionFactory $lockCollectionFactory
     * @param Data\LockSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceLock $resource,
        LockFactory $lockFactory,
        Data\LockInterfaceFactory $dataLockFactory,
        LockCollectionFactory $lockCollectionFactory,
        Data\LockSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->lockFactory = $lockFactory;
        $this->lockCollectionFactory = $lockCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataLockFactory = $dataLockFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * Save Lock data
     *
     * @param \Cleargo\FindYourLock\Api\Data\LockInterface $lock
     * @return Lock
     * @throws CouldNotSaveException
     */
    public function save(\Cleargo\FindYourLock\Api\Data\LockInterface $lock)
    {
        $storeId = $this->storeManager->getStore()->getId();
        $lock->setStoreId($storeId);
        try {
            $this->resource->save($lock);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $lock;
    }

    /**
     * Load Lock data by given Lock Identity
     *
     * @param string $lockId
     * @return Lock
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($lockId)
    {
        $lock = $this->lockFactory->create();
        $lock->load($lockId);
        if (!$lock->getId()) {
            throw new NoSuchEntityException(__('Lock Lock with id "%1" does not exist.', $lockId));
        }
        return $lock;
    }
    /**
     * Load Lock data by given Lock Identifier
     *
     * @param string $lockId
     * @return Lock
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByIdentifier($urlKey, $storeId)
    {
        $identifier = urldecode($urlKey) ;
        $collection = $this->lockCollectionFactory->create();
        $collection->addStoreFilter($storeId)->addFieldToFilter('identifier',$identifier);
        $lock = $collection->getFirstItem();
        return  $lock;
    }

    /**
     * Load Lock data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Cleargo\FindYourLock\Model\ResourceModel\Lock\Collection
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->lockCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            $fields = [];
            $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), true);
                    continue;
                }
                $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                $fields[] = $filter->getField();
                $conditions[] = [$condition => $filter->getValue()];
            }
            if ($fields) {
                $collection->addFieldToFilter(
                    $fields,
                    $conditions
                );
            }
        }
       // echo($collection->getSelect()->__toString());
       // die();
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
        $locks = [];
        /** @var Lock $lockModel */
        foreach ($collection as $lockModel) {
            $locks[] = $lockModel;
        }
        $searchResults->setItems($locks);
        return $searchResults;
    }

    /**
     * Delete Lock
     *
     * @param \Cleargo\FindYourLock\Api\Data\LockInterface $lock
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(\Cleargo\FindYourLock\Api\Data\LockInterface $lock)
    {
        try {
            $this->resource->delete($lock);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Lock by given Lock Identity
     *
     * @param string $lockId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($lockId)
    {
        return $this->delete($this->getById($lockId));
    }
}
