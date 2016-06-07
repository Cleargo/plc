<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Model;

use Cleargo\DealerNetwork\Api\Data;
use Cleargo\DealerNetwork\Api\BrandRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Cleargo\DealerNetwork\Model\ResourceModel\Brand as ResourceBrand;
use Cleargo\DealerNetwork\Model\ResourceModel\Brand\CollectionFactory as BrandCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class BrandRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BrandRepository implements BrandRepositoryInterface
{
    /**
     * @var ResourceBrand
     */
    protected $resource;

    /**
     * @var BrandFactory
     */
    protected $brandFactory;

    /**
     * @var BrandCollectionFactory
     */
    protected $brandCollectionFactory;

    /**
     * @var Data\BrandSearchResultsInterfaceFactory
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
     * @var \Cleargo\DealerNetwork\Api\Data\BrandInterfaceFactory
     */
    protected $dataBrandFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param ResourceBrand $resource
     * @param BrandFactory $brandFactory
     * @param Data\BrandInterfaceFactory $dataBrandFactory
     * @param BrandCollectionFactory $brandCollectionFactory
     * @param Data\BrandSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceBrand $resource,
        BrandFactory $brandFactory,
        Data\BrandInterfaceFactory $dataBrandFactory,
        BrandCollectionFactory $brandCollectionFactory,
        Data\BrandSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->brandFactory = $brandFactory;
        $this->brandCollectionFactory = $brandCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataBrandFactory = $dataBrandFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * Save Brand data
     *
     * @param \Cleargo\DealerNetwork\Api\Data\BrandInterface $brand
     * @return Brand
     * @throws CouldNotSaveException
     */
    public function save(\Cleargo\DealerNetwork\Api\Data\BrandInterface $brand)
    {
        $storeId = $this->storeManager->getStore()->getId();
        $brand->setStoreId($storeId);
        try {
            $this->resource->save($brand);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $brand;
    }

    /**
     * Load Brand data by given Brand Identity
     *
     * @param string $brandId
     * @return Brand
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($brandId)
    {
        $brand = $this->brandFactory->create();
        $brand->load($brandId);
        if (!$brand->getId()) {
            throw new NoSuchEntityException(__('Dealer Brand with id "%1" does not exist.', $brandId));
        }
        return $brand;
    }

    /**
     * Load Brand data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Cleargo\DealerNetwork\Model\ResourceModel\Brand\Collection
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->brandCollectionFactory->create();
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
        /** @var Brand $brandModel */
        foreach ($collection as $brandModel) {
            $countries[] = $brandModel;
        }
        $searchResults->setItems($countries);
        return $searchResults;
    }

    /**
     * Delete Brand
     *
     * @param \Cleargo\DealerNetwork\Api\Data\BrandInterface $brand
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(\Cleargo\DealerNetwork\Api\Data\BrandInterface $brand)
    {
        try {
            $this->resource->delete($brand);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Brand by given Brand Identity
     *
     * @param string $brandId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($brandId)
    {
        return $this->delete($this->getById($brandId));
    }
}
