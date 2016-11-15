<?php
/**
 * Copyright ? 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\BrandList\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\View\Element\Template;
use Magento\Eav\Model\Config;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use \Magento\Catalog\Model\Product\AttributeSet\Options as attributeSetOptions;
use \Magento\Catalog\Model\Product as productModel;

class Listing extends Template
{
    /**
     * @var string
     */
    protected $_template = 'brand/list.phtml';

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
     * @var \Magento\Eav\Model\Config
     */
    protected $_eavConfig;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected $_productCollectionFactory;

    protected $_attributeSetOptions;

    public function __construct(
        Context $context,
        SearchCriteriaBuilder $_searchCriteriaBuilder,
        FilterBuilder $_filterBuilder,
        FilterGroupBuilder $_filterGroupBuilder,
        SortOrderBuilder $_sortOrderBuilder,
        Config $_eavConfig,
        CollectionFactory $_productCollectionFactory,
        attributeSetOptions $_attributeSetOptions,
        array $data = []
    ) {
        $this->_searchCriteriaBuilder = $_searchCriteriaBuilder;
        $this->_filterBuilder = $_filterBuilder;
        $this->_filterGroupBuilder = $_filterGroupBuilder;
        $this->_sortOrderBuilder = $_sortOrderBuilder;
        $this->_eavConfig = $_eavConfig;
        $this->_productCollectionFactory = $_productCollectionFactory;
        $this->_attributeSetOptions = $_attributeSetOptions;
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

    public function getAttrSetLabelFromProduct(productModel $product){
        $optionId = $product->getAttributeSetId();
        $attr = $product->getResource()->getAttribute('attribute_set_id');
        $optionText = '';
        if ($attr->usesSource()) {
            $optionText = $attr->getSource()->getOptionText($optionId);
        }
        return $optionText;
    }

    public function getBrandLabelFromProduct(productModel $product){
        $optionId = $product->getBrand();
        $attr = $product->getResource()->getAttribute('brand');
        $optionText = '';
        if ($attr->usesSource()) {
            $optionText = $attr->getSource()->getOptionText($optionId);
        }
        return $optionText;
    }

    public function getAllAttrSetId(){
        return $this->_attributeSetOptions->toOptionArray();
    }

    public function getAllBrandByAttrSet($attrId){
        $productsInstance = $this->_productCollectionFactory->create();
        $products = $productsInstance
            ->addAttributeToFilter('attribute_set_id',$attrId)
            ->groupByAttribute('brand')
            ->load()
            ;
        $returnArr = array();
        foreach ($products as $product){
            $brandLabel = $this->getBrandLabelFromProduct($product);
            foreach(range('A','Z') as $i) {
                if(substr($brandLabel,0,1) == $i) {
                    $temp['group'] = $i;
                    $temp['id'] = $product->getBrand();
                    $temp['label'] = $brandLabel;
                    $returnArr[] = $temp;
                }
            }
        }
        return $returnArr;
    }

    public function getAllBrand(){
        $attribute = $this->_eavConfig->getAttribute('catalog_product', 'brand');
        $options = $attribute->getSource()->getAllOptions();
        $returnArr = array();
        foreach ($options as $option){
            foreach(range('A','Z') as $i) {
                $temp = [];
                if(isset($option['label'][0])){
                    if($option['label'][0] == $i) {
                        $temp['group'] = $i;
                        $temp['id'] = $option['value'];
                        $temp['label'] = $option['label'];
                        $returnArr[] = $temp;
                    }
                }
            }
        }

        return     $returnArr;
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
            $breadcrumbsBlock->addCrumb('dealer_dealer', ['label' => __('Brand List'), 'title' => __('Brand List')]);
        }
    }
}