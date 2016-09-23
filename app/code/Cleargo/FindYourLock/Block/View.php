<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 10/6/2016
 * Time: 5:03 PM
 */
namespace Cleargo\FindYourLock\Block;

use \Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SortOrderBuilder;

class View extends \Magento\Framework\View\Element\Template
{ 

    protected $_template = 'lock/detail.phtml';

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
     * @var \Cleargo\FindYourLock\Helper\Data
     */
    protected $_lockHelper;

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
        \Cleargo\FindYourLock\Helper\Data $_lockHelper,
        \Magento\Framework\Registry $registry,
        SearchCriteriaBuilder $_searchCriteriaBuilder,
        FilterBuilder $_filterBuilder,
        FilterGroupBuilder $_filterGroupBuilder,
        SortOrderBuilder $_sortOrderBuilder,
        array $data = []
    ) {
        $this->_regionRepository = $_regionRepository;
        $this->_districtRepository = $_districtRepository;
        $this->_lockRepository = $_lockRepository;
        $this->_lockHelper = $_lockHelper;
        $this->_coreRegistry = $registry;
        $this->_searchCriteriaBuilder = $_searchCriteriaBuilder;
        $this->_filterBuilder = $_filterBuilder;
        $this->_filterGroupBuilder = $_filterGroupBuilder;
        $this->_sortOrderBuilder = $_sortOrderBuilder;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        $this->_addBreadcrumbs();
        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle) {
            $pageMainTitle->setPageTitle($this->getCurrentLock()->getName2().' '.$this->getCurrentLock()->getName());
        }
        return parent::_prepareLayout();
    }

    public function getProductUrl(){
        if(!$this->getCurrentLock()->getProductId()){
            return false;
        }
        return $this->_lockHelper->getProductUrl($this->getCurrentLock()->getProductId());
    }
    public function getBackUrl(){
        return $this->_lockHelper->getBackUrl();
    }
    
    public function getDistrictName(){
        return $this->_districtRepository->getById($this->getCurrentLock()->getDistrictId())->getName();
    }

    public function getRegionName(){

        return $this->_regionRepository->getById($this->_districtRepository->getById($this->getCurrentLock()->getDistrictId())->getRegionId())->getName();
    }

    public function getCurrentLock()
    {
        if (!$this->hasData('current_lock')) {
            $this->setData('current_lock', $this->_coreRegistry->registry('current_lock'));
        }
        return $this->getData('current_lock');
    }

    public function getImageLock()
    {
        if (!$this->hasData('image_lock')) {
            $this->setData('image_lock', $this->_coreRegistry->registry('image_lock'));
        }
        return $this->getData('image_lock');
    }

    protected function _addBreadcrumbs()
    {
        $lock = $this->getCurrentLock();
        if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbsBlock->addCrumb(
                'home',
                [
                    'label' => __('Home'),
                    'title' => __('Go to Home Page'),
                    'link' => $this->_storeManager->getStore()->getBaseUrl()
                ]
            );
            $breadcrumbsBlock->addCrumb('lock_lock', ['label' => __('Find Your Lock'), 'title' => __('Find Your Lock'),'link' => $this->_storeManager->getStore()->getBaseUrl().'lock/finder']);
            $breadcrumbsBlock->addCrumb('lock_view', ['label' => $lock->getName() , 'title' => $lock->getName() ]);
        }
    }
}