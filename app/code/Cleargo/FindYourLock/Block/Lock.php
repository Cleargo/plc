<?php
/**
 * Copyright ? 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Block;

use \Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SortOrderBuilder;

class Lock extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'lock/lock.phtml';

    /*
     * @var \Cleargo\FindYourLock\Model\RegionRepository
     */
    protected $_regionRepository;

    /*
     * @var \Cleargo\FindYourLock\Model\DistrictRepository
     */
    protected $_districtRepository;

    /*
     * @var \Cleargo\FindYourLock\Model\LockRepository
     */
    protected $_lockRepository;

    /*
     * @var \Cleargo\FindYourLock\Model\BrandRepository
     */


    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $_searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\Api\FilterBuilder
     */
    protected $_filterBuilder;

    /**
     * @var \Magento\Framework\Api\Search\FilterGroupBuilder
     */
    protected $_filterGroupBuilder;

    /**
     * @var \Magento\Framework\Api\SortOrderBuilder
     */
    protected $_sortOrderBuilder;

    public function __construct(
        Context $context,
        \Cleargo\FindYourLock\Model\RegionRepository $_regionRepository,
        \Cleargo\FindYourLock\Model\DistrictRepository $_districtRepository,
        \Cleargo\FindYourLock\Model\LockRepository $_lockRepository,
        SearchCriteriaBuilder $_searchCriteriaBuilder,
        FilterBuilder $_filterBuilder,
        FilterGroupBuilder $_filterGroupBuilder,
        SortOrderBuilder $_sortOrderBuilder,
        array $data = []
    ) {
        $this->_regionRepository = $_regionRepository;
        $this->_districtRepository = $_districtRepository;
        $this->_lockRepository = $_lockRepository;
        $this->_searchCriteriaBuilder = $_searchCriteriaBuilder;
        $this->_filterBuilder = $_filterBuilder;
        $this->_filterGroupBuilder = $_filterGroupBuilder;
        $this->_sortOrderBuilder = $_sortOrderBuilder;
        parent::__construct($context, $data);
    }

    /**
     * Prepare global layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->_addBreadcrumbs();
        return parent::_prepareLayout();
    }

    /**
     * Get list of regions
     *
     * @return \Cleargo\FindYourLock\Api\Data\RegionInterface[]
     */
    public function getRegionList() {
        $storeFilter = $this->_filterBuilder
            ->setField('store_id')
            ->setValue($this->getCurrentStoreId())
            ->create();

        $activeFilter = $this->_filterBuilder
            ->setField('is_active')
            ->setValue(true)
            ->create();

        $filterGroup[] = $this->_filterGroupBuilder
            ->addFilter($storeFilter)
            ->create();

        $filterGroup[] = $this->_filterGroupBuilder
            ->addFilter($activeFilter)
            ->create();

        $regionParam = $this->getRequest()->getParam('region');
        if($regionParam != null && $regionParam != "") {
            $regionParam = explode(',',$regionParam);

            $regionFilter = $this->_filterBuilder
                ->setField('main_table.region_id')
                ->setConditionType('in')
                ->setValue($regionParam)
                ->create();

            $filterGroup[] = $this->_filterGroupBuilder
                ->addFilter($regionFilter)
                ->create();
        }

        $sortOrder = $this->_sortOrderBuilder
            ->setField('sort_order')
            ->setDirection(SortOrder::SORT_ASC)
            ->create();

        $searchCriteria = $this->_searchCriteriaBuilder
            ->setFilterGroups($filterGroup)
            ->addSortOrder($sortOrder)
            ->create();

        $regions = $this->_regionRepository->getList($searchCriteria)->getItems();

        /*
        $region_list = [];
        foreach($regions as $region) {
            $region_list[] = $region->getData();
        }
        */
        return $regions;
    }

    /**
     * Get list of districts for the region
     *
     * @return \Cleargo\FindYourLock\Api\Data\DistrictInterface[]
     */
    public function getDistrictList($region) {
        $storeFilter = $this->_filterBuilder
            ->setField('store_id')
            ->setValue($this->getCurrentStoreId())
            ->create();

        $activeFilter = $this->_filterBuilder
            ->setField('is_active')
            ->setValue(true)
            ->create();

        $regionFilter = $this->_filterBuilder
            ->setField('region_id')
            ->setValue($region->getId())
            ->create();

        $filterGroup[] = $this->_filterGroupBuilder
            ->addFilter($storeFilter)
            ->create();

        $filterGroup[] = $this->_filterGroupBuilder
            ->addFilter($activeFilter)
            ->create();

        $filterGroup[] = $this->_filterGroupBuilder
            ->addFilter($regionFilter)
            ->create();

        $districtParams = $this->getRequest()->getParam('district');
        if($districtParams != null && $districtParams != "") {
            $districtParams = explode(',',$districtParams);
            $districtFilterVal = [];
            foreach($districtParams as $districtParam) {
                $districtFilterVal[] = $districtParam;
            }

            if(!empty($districtFilterVal)) {
                $districtFilter = $this->_filterBuilder
                    ->setField('main_table.district_id')
                    ->setConditionType('in')
                    ->setValue($districtFilterVal)
                    ->create();

                $filterGroup[] = $this->_filterGroupBuilder
                    ->addFilter($districtFilter)
                    ->create();
            }
        }

        $sortOrder = $this->_sortOrderBuilder
            ->setField('sort_order')
            ->setDirection(SortOrder::SORT_ASC)
            ->create();

        $searchCriteria = $this->_searchCriteriaBuilder
            ->setFilterGroups($filterGroup)
            ->addSortOrder($sortOrder)
            ->create();

        $districts = $this->_districtRepository->getList($searchCriteria)->getItems();

        return $districts;
    }

    /**
     * Get list of locks for the district
     *
     * @return \Cleargo\FindYourLock\Api\Data\LockInterface[]
     */
    public function getLockList($district) {
        $storeFilter = $this->_filterBuilder
            ->setField('store_id')
            ->setValue($this->getCurrentStoreId())
            ->create();

        $activeFilter = $this->_filterBuilder
            ->setField('is_active')
            ->setValue(true)
            ->create();

        $districtFilter = $this->_filterBuilder
            ->setField('district_id')
            ->setValue($district->getId())
            ->create();

        $filterGroup[] = $this->_filterGroupBuilder
            ->addFilter($storeFilter)
            ->create();

        $filterGroup[] = $this->_filterGroupBuilder
            ->addFilter($activeFilter)
            ->create();

        $filterGroup[] = $this->_filterGroupBuilder
            ->addFilter($districtFilter)
            ->create();

        $keywordParam = $this->getRequest()->getParam('keyword');
        if($keywordParam != null && $keywordParam != "") {

            $keywordFilter = $this->_filterBuilder
                ->setField('main_table.address')
                ->setConditionType('like')
                ->setValue('%'.$keywordParam.'%')
                ->create();

            $keywordFilter2 = $this->_filterBuilder
                ->setField('main_table.name')
                ->setConditionType('like')
                ->setValue('%'.$keywordParam.'%')
                ->create();
            $keywordFilter3 = $this->_filterBuilder
                ->setField('main_table.name2')
                ->setConditionType('like')
                ->setValue('%'.$keywordParam.'%')
                ->create();
            $keywordFilter4 = $this->_filterBuilder
                ->setField('main_table.brand')
                ->setConditionType('like')
                ->setValue('%'.$keywordParam.'%')
                ->create();





            $filterGroup[] = $this->_filterGroupBuilder
                ->addFilter($keywordFilter)
                ->addFilter($keywordFilter2)
                ->addFilter($keywordFilter3)
                ->addFilter($keywordFilter4)
                ->create();
        }

        $sortOrder = $this->_sortOrderBuilder
            ->setField('sort_order')
            ->setDirection(SortOrder::SORT_ASC)
            ->create();

        $searchCriteria = $this->_searchCriteriaBuilder
            ->setFilterGroups($filterGroup)
            ->addSortOrder($sortOrder)
            ->create();

        $locks = $this->_lockRepository->getList($searchCriteria)->getItems();

        return $locks;
    }

    /**
     * Get number of locks for the region
     *
     * @return int
     */
    public function getLockCount($region) {
        $districts = $this->getDistrictList($region);

        $lock_num = 0;
        foreach($districts as $district) {
            $lock_num += count($this->getLockList($district));
        }

        return $lock_num;
    }

    /**
     * Get list of keywords for current store
     *
     * @return \Cleargo\FindYourLock\Api\Data\LockInterface[]
     */


    /**
     * Get current store name.
     *
     * @return int
     */
    public function getCurrentStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * Prepare breadcrumbs
     *
     * @return void
     */
    protected function _addBreadcrumbs()
    {
        if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbsBlock->addCrumb(
                'home',
                [
                    'label' => __('Home'),
                    'title' => __('Go to Home Page'),
                    'link' => $this->_storeManager->getStore()->getBaseUrl()
                ]
            );
            $breadcrumbsBlock->addCrumb('lock_lock', ['label' => __('Find Your Lock'), 'title' => __('Find Your Lock')]);
        }
    }
}