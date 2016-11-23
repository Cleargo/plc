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

class Thank extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'warranty/thank.phtml';

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

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $_request;
    protected $_question;
    protected $_product;

    public function __construct(
        Context $context,
        SearchCriteriaBuilder $_searchCriteriaBuilder,
        FilterBuilder $_filterBuilder,
        FilterGroupBuilder $_filterGroupBuilder,
        SortOrderBuilder $_sortOrderBuilder,
        \Magento\Framework\App\Request\Http $request,
        \Cleargo\Warranty\Model\Warranty\Source\QuestionType $_question,
        \Cleargo\Warranty\Model\Warranty\Source\ProductType $_product,
        array $data = []
    ) {
        $this->_searchCriteriaBuilder = $_searchCriteriaBuilder;
        $this->_filterBuilder = $_filterBuilder;
        $this->_filterGroupBuilder = $_filterGroupBuilder;
        $this->_request = $request;
        $this->_question = $_question;
        $this->_product = $_product;
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
     * Get Inputed Value
     *
     * @return []
     */
    public function getInputedValue() {
        return $this->_request->getPost();
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
            $breadcrumbsBlock->addCrumb('warranty_review', ['label' => __('MT5 Registration'), 'title' => __('MT5 Registration')]);
        }
    }
}