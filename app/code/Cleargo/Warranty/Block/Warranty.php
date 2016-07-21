<?php
/**
 * Copyright ? 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\Warranty\Block;

use \Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SortOrderBuilder;

class Warranty extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'warranty/form.phtml';

    /*
     * @var \Cleargo\Warranty\Model\CountryRepository
     */
    protected $_countryRepository;

    /*
     * @var \Cleargo\Warranty\Model\RegionRepository
     */
    protected $_regionRepository;

    /*
     * @var \Cleargo\Warranty\Model\DealerRepository
     */
    protected $_dealerRepository;

    /*
     * @var \Cleargo\Warranty\Model\BrandRepository
     */
    protected $_brandRepository;

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
        SearchCriteriaBuilder $_searchCriteriaBuilder,
        FilterBuilder $_filterBuilder,
        FilterGroupBuilder $_filterGroupBuilder,
        SortOrderBuilder $_sortOrderBuilder,
        array $data = []
    ) {
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
     * Get list of countries
     *
     * @return \Cleargo\Warranty\Api\Data\CountryInterface[]
     */
    public function getCountryList() {
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

        $countryParam = $this->getRequest()->getParam('country');
        if($countryParam != null && $countryParam != "") {
            $countryParam = explode(',',$countryParam);

            $countryFilter = $this->_filterBuilder
                ->setField('main_table.country_id')
                ->setConditionType('in')
                ->setValue($countryParam)
                ->create();

            $filterGroup[] = $this->_filterGroupBuilder
                ->addFilter($countryFilter)
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

        $countries = $this->_countryRepository->getList($searchCriteria)->getItems();

        /*
        $country_list = [];
        foreach($countries as $country) {
            $country_list[] = $country->getData();
        }
        */
        return $countries;
    }

    /**
     * Get list of regions for the country
     *
     * @return \Cleargo\Warranty\Api\Data\RegionInterface[]
     */
    public function getRegionList($country) {
        $storeFilter = $this->_filterBuilder
            ->setField('store_id')
            ->setValue($this->getCurrentStoreId())
            ->create();

        $activeFilter = $this->_filterBuilder
            ->setField('is_active')
            ->setValue(true)
            ->create();

        $countryFilter = $this->_filterBuilder
            ->setField('country_id')
            ->setValue($country->getId())
            ->create();

        $filterGroup[] = $this->_filterGroupBuilder
            ->addFilter($storeFilter)
            ->create();

        $filterGroup[] = $this->_filterGroupBuilder
            ->addFilter($activeFilter)
            ->create();

        $filterGroup[] = $this->_filterGroupBuilder
            ->addFilter($countryFilter)
            ->create();

        $regionParams = $this->getRequest()->getParam('region');
        if($regionParams != null && $regionParams != "") {
            $regionParams = explode(',',$regionParams);
            $regionFilterVal = [];
            foreach($regionParams as $regionParam) {
                $regionParam = explode('_',$regionParam);
                if($regionParam[0] == $country->getId()) {
                    $regionFilterVal[] = $regionParam[1];
                }
            }

            if(!empty($regionFilterVal)) {
                $regionFilter = $this->_filterBuilder
                    ->setField('main_table.region_id')
                    ->setConditionType('in')
                    ->setValue($regionFilterVal)
                    ->create();

                $filterGroup[] = $this->_filterGroupBuilder
                    ->addFilter($regionFilter)
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

        $regions = $this->_regionRepository->getList($searchCriteria)->getItems();

        return $regions;
    }

    /**
     * Get list of dealers for the region
     *
     * @return \Cleargo\Warranty\Api\Data\DealerInterface[]
     */
    public function getDealerList($region) {
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

        $brandParam = $this->getRequest()->getParam('brand');
        if($brandParam != null && $brandParam != "") {
            $brandParam = explode(',',$brandParam);

            $brandFilter = $this->_filterBuilder
                ->setField('brand_id')
                ->setConditionType('in')
                ->setValue($brandParam)
                ->create();

            $filterGroup[] = $this->_filterGroupBuilder
                ->addFilter($brandFilter)
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

        $dealers = $this->_dealerRepository->getList($searchCriteria)->getItems();

        return $dealers;
    }

    /**
     * Get number of dealers for the country
     *
     * @return int
     */
    public function getDealerCount($country) {
        $regions = $this->getRegionList($country);

        $dealer_num = 0;
        foreach($regions as $region) {
            $dealer_num += count($this->getDealerList($region));
        }

        return $dealer_num;
    }

    /**
     * Get list of brands for current store
     *
     * @return \Cleargo\Warranty\Api\Data\DealerInterface[]
     */
    public function getBrandList() {
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

        $sortOrder = $this->_sortOrderBuilder
            ->setField('sort_order')
            ->setDirection(SortOrder::SORT_ASC)
            ->create();

        $searchCriteria = $this->_searchCriteriaBuilder
            ->setFilterGroups($filterGroup)
            ->addSortOrder($sortOrder)
            ->create();

        $brands = $this->_brandRepository->getList($searchCriteria)->getItems();

        return $brands;
    }

    /**
     * Get current store name.
     *
     * @return int
     */
    public function getCurrentStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    public function getFormAction()
    {
        return $this->getUrl('warranty/form/review', ['_secure' => true]);
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
            $breadcrumbsBlock->addCrumb('dealer_dealer', ['label' => __('Dealer Network'), 'title' => __('Dealer Network')]);
        }
    }
}