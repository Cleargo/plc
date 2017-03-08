<?php
namespace Cleargo\ProductPdf\Block\Adminhtml\Catalog\Product\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;

class Pdf extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'product/edit/upload.phtml';

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $_coreRegistry = null;
    protected $formKey ;
    protected $_pdfRepository;
    /**
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        \Cleargo\ProductPdf\Model\PdfRepository $_pdfRepository,
        array $data = []
    )
    {
        $this->_coreRegistry = $registry;
        $this->formKey = $context->getFormKey();
        $this->_pdfRepository = $_pdfRepository;
        parent::__construct($context, $data);
    }

    public function getFormKey(){
        return $this->formKey->getFormKey();
    }

    public function getPdfArr(){
        $currentPdfs = $this->_pdfRepository->getByProductId($this->getRequest()->getParam('id'),$this->getRequest()->getParam('store'));
        return $currentPdfs;
    }
    /**
     * Retrieve product
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        return $this->_coreRegistry->registry('current_product');
    }

    public function getUrlPreffix()
    {
        return $this->_urlBuilder->getBaseUrl(['_type' =>  \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]);
    }

    public function getAjaxPath(){
        return $this->getUrl('pdf/pdf/save',
                                [
                                    'id'=> $this->getRequest()->getParam('id'),
                                    'store'=> $this->getRequest()->getParam('store'),
                                ]);
    }
}