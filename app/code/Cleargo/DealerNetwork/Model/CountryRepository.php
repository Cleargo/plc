<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Model;

use Cleargo\DealerNetwork\Api\Data;
use Cleargo\DealerNetwork\Api\CountryRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Cleargo\DealerNetwork\Model\ResourceModel\Country as ResourceCountry;
use Cleargo\DealerNetwork\Model\ResourceModel\Country\CollectionFactory as CountryCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class CountryRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CountryRepository implements CountryRepositoryInterface
{
    /**
     * @var ResourceCountry
     */
    protected $resource;

    /**
     * @var CountryFactory
     */
    protected $countryFactory;

    /**
     * @var CountryCollectionFactory
     */
    protected $countryCollectionFactory;

    /**
     * @var Data\CountrySearchResultsInterfaceFactory
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
     * @var \Cleargo\DealerNetwork\Api\Data\CountryInterfaceFactory
     */
    protected $dataCountryFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param ResourceCountry $resource
     * @param CountryFactory $countryFactory
     * @param Data\CountryInterfaceFactory $dataCountryFactory
     * @param CountryCollectionFactory $countryCollectionFactory
     * @param Data\CountrySearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceCountry $resource,
        CountryFactory $countryFactory,
        Data\CountryInterfaceFactory $dataCountryFactory,
        CountryCollectionFactory $countryCollectionFactory,
        Data\CountrySearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->countryFactory = $countryFactory;
        $this->countryCollectionFactory = $countryCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataCountryFactory = $dataCountryFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * Save Country data
     *
     * @param \Cleargo\DealerNetwork\Api\Data\CountryInterface $country
     * @return Country
     * @throws CouldNotSaveException
     */
    public function save(\Cleargo\DealerNetwork\Api\Data\CountryInterface $country)
    {
        $storeId = $this->storeManager->getStore()->getId();
        $country->setStoreId($storeId);
        try {
            $this->resource->save($country);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $country;
    }

    /**
     * Load Country data by given Country Identity
     *
     * @param string $countryId
     * @return Country
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($countryId)
    {
        $country = $this->countryFactory->create();
        $country->load($countryId);
        if (!$country->getId()) {
            throw new NoSuchEntityException(__('Dealer Country with id "%1" does not exist.', $countryId));
        }
        return $country;
    }

    /**
     * Load Country data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Cleargo\DealerNetwork\Model\ResourceModel\Country\Collection
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->countryCollectionFactory->create();
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
        $countries = [];
        /** @var Country $countryModel */
        foreach ($collection as $countryModel) {
            $countries[] = $countryModel;
        }
        $searchResults->setItems($countries);
        return $searchResults;
    }

    /**
     * Delete Country
     *
     * @param \Cleargo\DealerNetwork\Api\Data\CountryInterface $country
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(\Cleargo\DealerNetwork\Api\Data\CountryInterface $country)
    {
        try {
            $this->resource->delete($country);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Country by given Country Identity
     *
     * @param string $countryId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($countryId)
    {
        return $this->delete($this->getById($countryId));
    }
}
