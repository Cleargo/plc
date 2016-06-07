<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Block\Adminhtml\Dealer\Edit;

/**
 * Adminhtml dealer dealer edit form
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Cleargo\DealerNetwork\Model\Dealer\Source\Region
     */
    protected $_regionSource;

    /**
     * @var \Cleargo\DealerNetwork\Model\Dealer\Source\Brand
     */
    protected $_brandSource;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Cleargo\DealerNetwork\Model\Dealer\Source\Region $regionSource,
        \Cleargo\DealerNetwork\Model\Dealer\Source\Brand $brandSource,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_regionSource = $regionSource;
        $this->_brandSource = $brandSource;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('dealer_form');
        $this->setTitle(__('Dealer Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('dealer_dealer');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $form->setHtmlIdPrefix('dealer_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getDealerId()) {
            $fieldset->addField('dealer_id', 'hidden', ['name' => 'dealer_id']);
        }

        $fieldset->addField(
            'name',
            'text',
            ['name' => 'name', 'label' => __('Dealer Name'), 'title' => __('Dealer Name'), 'required' => true]
        );

        $fieldset->addField(
            'address',
            'textarea',
            ['name' => 'address', 'label' => __('Dealer Address'), 'title' => __('Dealer Address'), 'required' => true]
        );

        $fieldset->addField(
            'tel',
            'text',
            ['name' => 'tel', 'label' => __('Dealer Tel'), 'title' => __('Dealer Tel')]
        );

        $fieldset->addField(
            'identifier',
            'text',
            [
                'name' => 'identifier',
                'label' => __('Identifier'),
                'title' => __('Identifier'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'region_id',
            'select',
            [
                'label' => __('Region'),
                'title' => __('Region'),
                'name' => 'region_id',
                'required' => true,
                'options' => $this->_getRegionOptions()
            ]
        );

        $fieldset->addField(
            'brand_id',
            'multiselect',
            [
                'name' => 'brand_id[]',
                'label' => __('Brands'),
                'title' => __('Brands'),
                'required' => true,
                'values' => $this->_getBrandOptions()
            ]
        );

        /* Check is single store mode */
        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'store_id',
                'multiselect',
                [
                    'name' => 'stores[]',
                    'label' => __('Store View'),
                    'title' => __('Store View'),
                    'required' => true,
                    'values' => $this->_systemStore->getStoreValuesForForm(false, true)
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField(
                'store_id',
                'hidden',
                ['name' => 'stores[]', 'value' => $this->_storeManager->getStore(true)->getId()]
            );
            $model->setStoreId($this->_storeManager->getStore(true)->getId());
        }

        $fieldset->addField(
            'sort_order',
            'text',
            [
                'label' => __('Sort Order'),
                'title' => __('Sort Order'),
                'name' => 'sort_order',
                'required' => true,
                'class' => 'validate-digits'
            ]
        );
        if (!$model->getId()) {
            $model->setData('sort_order', '0');
        }

        $fieldset->addField(
            'is_active',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'is_active',
                'required' => true,
                'options' => ['1' => __('Enabled'), '0' => __('Disabled')]
            ]
        );
        if (!$model->getId()) {
            $model->setData('is_active', '1');
        }

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Get dropdown options for region
     *
     * @return array
     */
    protected function _getRegionOptions() {
        $availableOptions = $this->_regionSource->toOptionArray();
        $options = [];
        foreach($availableOptions as $availableOption) {
            $options[$availableOption['value']] = __($availableOption['label']);
        }
        return $options;
    }

    /**
     * Get dropdown options for brand
     *
     * @return array
     */
    protected function _getBrandOptions() {
        $availableOptions = $this->_brandSource->toOptionArray();
        return $availableOptions;
    }
}
