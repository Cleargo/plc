<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Model;

use Cleargo\DealerNetwork\Api\Data;
use Cleargo\DealerNetwork\Api\DealerRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Cleargo\DealerNetwork\Model\ResourceModel\Dealer as ResourceDealer;
use Cleargo\DealerNetwork\Model\ResourceModel\Dealer\CollectionFactory as DealerCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class DealerRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class DealerRepository implements DealerRepositoryInterface
{
    /**
     * @var ResourceDealer
     */
    protected $resource;

    /**
     * @var DealerFactory
     */
    protected $dealerFactory;

    /**
     * @var DealerCollectionFactory
     */
    protected $dealerCollectionFactory;

    /**
     * @var Data\DealerSearchResultsInterfaceFactory
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
     * @var \Cleargo\DealerNetwork\Api\Data\DealerInterfaceFactory
     */
    protected $dataDealerFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param ResourceDealer $resource
     * @param DealerFactory $dealerFactory
     * @param Data\DealerInterfaceFactory $dataDealerFactory
     * @param DealerCollectionFactory $dealerCollectionFactory
     * @param Data\DealerSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceDealer $resource,
        DealerFactory $dealerFactory,
        Data\DealerInterfaceFactory $dataDealerFactory,
        DealerCollectionFactory $dealerCollectionFactory,
        Data\DealerSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->dealerFactory = $dealerFactory;
        $this->dealerCollectionFactory = $dealerCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataDealerFactory = $dataDealerFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * Save Dealer data
     *
     * @param \Cleargo\DealerNetwork\Api\Data\DealerInterface $dealer
     * @return Dealer
     * @throws CouldNotSaveException
     */
    public function save(\Cleargo\DealerNetwork\Api\Data\DealerInterface $dealer)
    {
        $storeId = $this->storeManager->getStore()->getId();
        $dealer->setStoreId($storeId);
        try {
            $this->resource->save($dealer);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $dealer;
    }

    /**
     * Load Dealer data by given Dealer Identity
     *
     * @param string $dealerId
     * @return Dealer
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($dealerId)
    {
        $dealer = $this->dealerFactory->create();
        $dealer->load($dealerId);
        if (!$dealer->getId()) {
            throw new NoSuchEntityException(__('Dealer Dealer with id "%1" does not exist.', $dealerId));
        }
        return $dealer;
    }

    /**
     * Load Dealer data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Cleargo\DealerNetwork\Model\ResourceModel\Dealer\Collection
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->dealerCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), true);
                    continue;
                } else if ($filter->getField() === 'brand_id') {
                    $valArr = [];
                    foreach ( $filter->getValue() as $val){
                        //$valArr[] =  array('finset'=>$val) ;
                        $collection->addFieldToFilter('brand_id', array('finset'=>$val) );
                    }

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

        $dealers = [];
        /** @var Dealer $dealerModel */
        foreach ($collection as $dealerModel) {
            $dealers[] = $dealerModel;
        }
        $searchResults->setItems($dealers);
        return $searchResults;
    }

    /**
     * Delete Dealer
     *
     * @param \Cleargo\DealerNetwork\Api\Data\DealerInterface $dealer
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(\Cleargo\DealerNetwork\Api\Data\DealerInterface $dealer)
    {
        try {
            $this->resource->delete($dealer);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Dealer by given Dealer Identity
     *
     * @param string $dealerId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($dealerId)
    {
        return $this->delete($this->getById($dealerId));
    }
}
