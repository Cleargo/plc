<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
namespace Manadev\Core\Blocks\Adminhtml\ExtensionControl;

use Manadev\Core\Model\Source\Status;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Data\CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var Status
     */
    private $sourceStatus;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Manadev\Core\Resources\ExtensionCollectionFactory $collectionFactory,
        Status $sourceStatus,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->sourceStatus = $sourceStatus;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct() {
        parent::_construct();
        $this->setId('extensionGrid');
        $this->setFilterVisibility(false);
        $this->setPagerVisibility(false);
    }


    /**
     * @return $this
     */
    protected function _prepareCollection() {
        $collection = $this->collectionFactory->create();
        $collection->setStore($this->_getStore());
        $this->setCollection($collection);

        parent::_prepareCollection();
        $this->getCollection()->setOrder('order', 'ASC');
        return $this;
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns() {
        $this->addColumn(
            'title',
            [
                'header' => __('Extension Name'),
                'sortable' => false,
                'filter' => false,
                'index' => 'title',
                'width' => '200px',
                'align' => 'left',
                'renderer' => '\Manadev\Core\Blocks\Adminhtml\ExtensionControl\Feature\ExtensionNameColumn',
            ]
        );
        $this->addColumn(
            'version',
            [
                'header' => __('Version'),
                'sortable' => false,
                'filter' => false,
                'index' => 'version',
                'width' => '200px',
                'align' => 'left',
            ]
        );
        $this->addColumn(
            'is_enabled',
            [
                'header' => __('Status'),
                'sortable' => false,
                'filter' => false,
                'name' => 'is_enabled',
                'index' => 'is_enabled',
                'width' => '50px',
                'align' => 'left',
                'type' => 'options',
                'options' => $this->sourceStatus->getOptions(),

                'editable' => true,
                'edit_only' => true,
                'renderer' => '\Manadev\Core\Blocks\Adminhtml\ExtensionControl\Feature\IsEnabledColumn',
                'column_css_class' => 'mc-unit',

                'default_store_label' => __('Same for All Stores'),
            ]
        );
        return parent::_prepareColumns();
    }

    public function getSerializeData() {
        $items = [];
        $extensionCollection = $this->collectionFactory->create();
        $extensionCollection->setStore($this->_getStore());
        foreach($extensionCollection->getItems() as $item) {
            $item->setData('is_enabled', (string)$item->getData('is_enabled'));
            $items[$item->getData('id')] = $item->toArray();
        }
        return $items;
    }

    /**
     * @param \Manadev\Sorting\Models\Method $row
     * @return string
     */
    public function getRowUrl($row) {
        return '';
    }

    protected function _getStore() {
        return $this->getRequest()->getParam('store', 0);
    }
}