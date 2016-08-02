<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 10/6/2016
 * Time: 5:03 PM
 */
namespace Cleargo\ProductPdf\Block;

use \Magento\Framework\View\Element\Template\Context;
use \Magento\Framework\Api\SortOrder;
use \Magento\Framework\Api\SearchCriteriaBuilder;
use \Magento\Framework\Api\FilterBuilder;
use \Magento\Framework\Api\Search\FilterGroupBuilder;
use \Magento\Framework\Api\SortOrderBuilder;

class Pdf extends \Magento\Framework\View\Element\Template{
    


    /*
     * @var \Cleargo\ProductPdf\Model\PdfRepository
     */
    protected $_pdfRepository;

    /*
     * @var \Cleargo\ProductPdf\Model\BrandRepository
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
        \Cleargo\ProductPdf\Model\PdfRepository $_pdfRepository,
        SearchCriteriaBuilder $_searchCriteriaBuilder,
        FilterBuilder $_filterBuilder,
        FilterGroupBuilder $_filterGroupBuilder,
        SortOrderBuilder $_sortOrderBuilder,
        array $data = []
    ) {
        $this->_pdfRepository = $_pdfRepository;
        $this->_searchCriteriaBuilder = $_searchCriteriaBuilder;
        $this->_filterBuilder = $_filterBuilder;
        $this->_filterGroupBuilder = $_filterGroupBuilder;
        $this->_sortOrderBuilder = $_sortOrderBuilder;
        parent::__construct($context, $data);
    }

    public function getMediaPrefix(){
        return $this->_urlBuilder->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]);
    }

    public function getPdfList() {
        $storeFilter = $this->_filterBuilder
            ->setField('store_id')
            ->setValue($this->getCurrentStoreId())
            ->create();

        $activeFilter = $this->_filterBuilder
            ->setField('is_active')
            ->setValue(true)
            ->create();

        $productFilter = $this->_filterBuilder
            ->setField('linked_product_id')
            ->setValue($this->getRequest()->getParam('id'))
            ->create();



        $filterGroup[] = $this->_filterGroupBuilder
            ->addFilter($storeFilter)
            ->create();

        $filterGroup[] = $this->_filterGroupBuilder
            ->addFilter($activeFilter)
            ->create();

        $filterGroup[] = $this->_filterGroupBuilder
            ->addFilter($productFilter)
            ->create();

        $sortOrder = $this->_sortOrderBuilder
            ->setField('sort_order')
            ->setDirection(SortOrder::SORT_ASC)
            ->create();

        $searchCriteria = $this->_searchCriteriaBuilder
            ->setFilterGroups($filterGroup)
            ->create();

        $regions = $this->_pdfRepository->getByProductId($this->getRequest()->getParam('id'),$this->getCurrentStoreId());
        /*
        $region_list = [];
        foreach($regions as $region) {
            $region_list[] = $region->getData();
        }
        */
        return $regions;
    }

}