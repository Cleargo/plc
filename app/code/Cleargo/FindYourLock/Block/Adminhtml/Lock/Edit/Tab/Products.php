<?php
namespace Cleargo\FindYourLock\Block\Adminhtml\Lock\Edit\Tab;
use Cleargo\FindYourLock\Model\LockFactory;
class Products extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;
    /** 
     * Lock factory
     *
     * @var LockFactory
     */
    protected $lockFactory;
    /**
     * @var  \Magento\Framework\Registry
     */
    protected $registry;
    protected $_objectManager = null;
    /**
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Framework\Registry $registry
     * @param LockFactory $attachmentFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        LockFactory $lockFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        array $data = []
    ) {
        $this->contactFactory = $lockFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->_objectManager = $objectManager;
        $this->registry = $registry;
        parent::__construct($context, $backendHelper, $data);
    }
    /**
     * _construct
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('productsGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        /*if ($this->getRequest()->getParam('lock_id')) {
            $this->setDefaultFilter(array('in_product' => 1));
        }*/
    }
    /**
     * add Column Filter To Collection
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_product') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $productIds));
            } else {
                if ($productIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $productIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
    /**
     * prepare collection
     */
    protected function _prepareCollection()
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('name');
        $collection->addAttributeToSelect('sku');
        $collection->addAttributeToSelect('price');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        /* @var $model \Cleargo\FindYourLock\Model\Slide */
        $model = $this->_objectManager->get('\Cleargo\FindYourLock\Model\Lock');
        $this->addColumn(
            'in_product',
            [
                'header_css_class' => 'a-center',
                'type' => 'checkbox',
                'name' => 'in_product',
                'align' => 'center',
                'index' => 'entity_id',
                'values' => $this->getSelectedProducts(),
            ]
        );
        $this->addColumn(
            'entity_id',
            [
                'header' => __('Product ID'),
                'type' => 'number',
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );
        $this->addColumn(
            'product_name',
            [
                'header' => __('Name'),
                'index' => 'name',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'sku',
            [
                'header' => __('Sku'),
                'index' => 'sku',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'price',
            [
                'header' => __('Price'),
                'type' => 'currency',
                'index' => 'price',
                'width' => '50px',
            ]
        );
        return parent::_prepareColumns();
    }
    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/productsgrid', ['_current' => true]);
    }
    /**
     * @param  object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return '';
    }
    protected function _getSelectedProducts()
    {
        $lock = $this->getLock();
        return $lock->getProductId($lock);
    }
    /**
     * Retrieve selected products
     *
     * @return array
     */
    public function getSelectedProducts()
    {
        $lock = $this->getLock();
        $selected = $lock->getProductId($lock);
        //var_dump([$selected]);die();

        return [$selected];
    }
    protected function getLock()
    {
        $lockId = $this->getRequest()->getParam('lock_id');
        $lock   = $this->contactFactory->create();
        if ($lockId) {
            $lock->load($lockId);
        }
        return $lock;
    }
    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }
    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return true;
    }
}