<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\PopupForm\Model;

use Cleargo\PopupForm\Api\Data;
use Cleargo\PopupForm\Api\OptionRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Cleargo\PopupForm\Model\ResourceModel\Option as ResourceOption;
use Cleargo\PopupForm\Model\ResourceModel\Option\CollectionFactory as OptionCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class OptionRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class OptionRepository implements OptionRepositoryInterface
{
    /**
     * @var ResourceOption
     */
    protected $resource;

    /**
     * @var OptionFactory
     */
    protected $optionFactory;

    /**
     * @var OptionCollectionFactory
     */
    protected $optionCollectionFactory;

    /**
     * @var Data\OptionSearchResultsInterfaceFactory
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
     * @var \Cleargo\PopupForm\Api\Data\OptionInterfaceFactory
     */
    protected $dataOptionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param ResourceOption $resource
     * @param OptionFactory $optionFactory
     * @param Data\OptionInterfaceFactory $dataOptionFactory
     * @param OptionCollectionFactory $optionCollectionFactory
     * @param Data\OptionSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceOption $resource,
        OptionFactory $optionFactory,
        Data\OptionInterfaceFactory $dataOptionFactory,
        OptionCollectionFactory $optionCollectionFactory,
        Data\OptionSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->optionFactory = $optionFactory;
        $this->optionCollectionFactory = $optionCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataOptionFactory = $dataOptionFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * Save Option data
     *
     * @param \Cleargo\PopupForm\Api\Data\OptionInterface $option
     * @return Option
     * @throws CouldNotSaveException
     */
    public function save(Data\OptionInterface $option)
    {
       
        $storeId = $this->storeManager->getStore()->getId();
        $option->setStoreId($storeId);
        try {
            $this->resource->save($option);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $option;
    }

    /**
     * Load Option data by given Option Identity
     *
     * @param string $optionId
     * @return Option
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($optionId)
    {
        $option = $this->optionFactory->create();
        $this->resource->load($option, $optionId);
        if (!$option->getId()) {
            throw new NoSuchEntityException(__('CMS Option with id "%1" does not exist.', $optionId));
        }
        return $option;
    }

    /**
     * Load Option data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Cleargo\PopupForm\Model\ResourceModel\Option\Collection
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->optionCollectionFactory->create();
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
        $options = [];
        /** @var Option $optionModel */
        foreach ($collection as $optionModel) {
            $optionData = $this->dataOptionFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $optionData,
                $optionModel->getData(),
                'Cleargo\PopupForm\Api\Data\OptionInterface'
            );
            $options[] = $this->dataObjectProcessor->buildOutputDataArray(
                $optionData,
                'Cleargo\PopupForm\Api\Data\OptionInterface'
            );
        }
        $searchResults->setItems($options);
        return $searchResults;
    }

    /**
     * Delete Option
     *
     * @param \Cleargo\PopupForm\Api\Data\OptionInterface $option
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Data\OptionInterface $option)
    {
        try {
            $this->resource->delete($option);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Option by given Option Identity
     *
     * @param string $optionId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($optionId)
    {
        return $this->delete($this->getById($optionId));
    }
}
