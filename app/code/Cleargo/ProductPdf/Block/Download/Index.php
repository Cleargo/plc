<?php 


namespace Cleargo\ProductPdf\Block\Download;



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
use Cleargo\ProductPdf\Model\ResourceModel\Pdf\CollectionFactory as pdfCollectionFactory;
use Magento\Framework\Filesystem;

class Index extends Template
{
    /**
     * @var string
     */
    protected $_template = 'download/index.phtml';

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

    protected $_productModel;

    protected $_pdfCollectionFactory;



    public function __construct(
        Context $context,
        SearchCriteriaBuilder $_searchCriteriaBuilder,
        FilterBuilder $_filterBuilder,
        FilterGroupBuilder $_filterGroupBuilder,
        SortOrderBuilder $_sortOrderBuilder,
        Config $_eavConfig,
        CollectionFactory $_productCollectionFactory,
        attributeSetOptions $_attributeSetOptions,
        pdfCollectionFactory $_pdfCollectionFactory,
        productModel $_productModel,
        array $data = []
    ) {
        $this->_searchCriteriaBuilder = $_searchCriteriaBuilder;
        $this->_filterBuilder = $_filterBuilder;
        $this->_filterGroupBuilder = $_filterGroupBuilder;
        $this->_sortOrderBuilder = $_sortOrderBuilder;
        $this->_eavConfig = $_eavConfig;
        $this->_productCollectionFactory = $_productCollectionFactory;
        $this->_attributeSetOptions = $_attributeSetOptions;
        $this->_pdfCollectionFactory = $_pdfCollectionFactory;
        $this->_productModel = $_productModel;
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

    public function cleanSpecialChar($string) {
        $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '_', $string); // Removes special chars.
    }

    public function getMideaDirectory()
    {
        return  $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath();
    }

    public function getFullMediaUrl($relative){
        return $this->_storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).$relative;
    }

    public function getFormatedFileSize($pdfId){
        return number_format(
                (float) filesize($this->getMideaDirectory() ."wysiwyg/pdf-download-page/preview/". $pdfId .".png") / 1024 /1024 ,
                2,
                '.',
                ''
            );
    }

    public function getPdfs(){
        $pdfs =  $this->_pdfCollectionFactory->create();
        $return = [] ;
        
        foreach ($pdfs as $pdf){
            $productId = $pdf->getProductId();
            $product = $this->_productModel->load($productId);
            if($product->getBrand()){
                $return[$product->getAttributeSetId()][$product->getBrand()][] = array( 'id' => $pdf->getId() , 'name' => $pdf->getName() ? $pdf->getName() : substr($pdf->getPdfPath(),24) , 'path' => $pdf->getPdfPath()) ;
            }
        }

        return $return;
    }

    public function checkUrlExist($file){
        $file_headers = @get_headers($file);
        if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
            $exists = false;
        }
        else {
            $exists = true;
        }
        return $exists;
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

    public function getBrandLabelFormId($optionId){
        $product = $this->_productModel;
        $attr = $product->getResource()->getAttribute('brand');
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

    public function uploadPdfPreviewImage($fullPathOfCurrentFile , $name){
       
    }

    public function getAllAttrSetId(){
        return $this->_attributeSetOptions->toOptionArray();
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
            $breadcrumbsBlock->addCrumb('pdf_download', ['label' => __('CATALOGUE & DOWNLOADS'), 'title' => __('CATALOGUE & DOWNLOADS')]);
        }
    }
}
