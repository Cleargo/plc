<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Block\Adminhtml\Lock\Edit;

/**
 * Adminhtml lock lock edit form
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Cleargo\FindYourLock\Model\Lock\Source\District
     */
    protected $_districtSource;

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
        \Cleargo\FindYourLock\Model\Lock\Source\District $districtSource,
        array $data = []
    ) {

        $this->_systemStore = $systemStore;
        $this->_districtSource = $districtSource;
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
        $this->setId('lock_form');
        $this->setTitle(__('Lock Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('lock_lock');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post','enctype' => 'multipart/form-data']]
        );

        $form->setHtmlIdPrefix('lock_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getLockId()) {
            $fieldset->addField('lock_id', 'hidden', ['name' => 'lock_id']);
        }

        $fieldset->addField(
            'name',
            'text',
            ['name' => 'name', 'label' => __('Lock Name'), 'title' => __('Lock Name'), 'required' => true]
        );

        $fieldset->addField(
            'name2',
            'text',
            ['name' => 'name2', 'label' => __('Lock Name2'), 'title' => __('Lock Name2'), 'required' => true]
        );
        $fieldset->addField(
            'address',
            'text',
            ['name' => 'address', 'label' => __('Lock Address'), 'title' => __('Lock Address'), 'required' => true]
        );
        $fieldset->addField(
            'year',
            'text',
            ['name' => 'year', 'label' => __('Lock year'), 'title' => __('Lock year')]
        );
        $fieldset->addField(
            'developer',
            'text',
            ['name' => 'developer', 'label' => __('Lock developer'), 'title' => __('Lock developer')]
        );
        $fieldset->addField(
            'unit',
            'text',
            ['name' => 'unit', 'label' => __('Lock unit'), 'title' => __('Lock unit')]
        );
        $fieldset->addField(
            'unit_per_floor',
            'text',
            ['name' => 'unit_per_floor', 'label' => __('Lock unit_per_floor'), 'title' => __('Lock unit_per_floor')]
        );$fieldset->addField(
            'height',
            'text',
            ['name' => 'height', 'label' => __('Lock height'), 'title' => __('Lock height')]
        );$fieldset->addField(
            'size',
            'text',
            ['name' => 'size', 'label' => __('Lock size'), 'title' => __('Lock size')]
        );$fieldset->addField(
            'brand',
            'text',
            ['name' => 'brand', 'label' => __('Lock brand'), 'title' => __('Lock brand')]
        );$fieldset->addField(
            'block',
            'text',
            ['name' => 'block', 'label' => __('Lock block'), 'title' => __('Lock block')]
        );$fieldset->addField(
            'thickness',
            'text',
            ['name' => 'thickness', 'label' => __('Lock thickness'), 'title' => __('Lock thickness')]
        );$fieldset->addField(
            'backset',
            'text',
            ['name' => 'backset', 'label' => __('Lock backset'), 'title' => __('Lock backset')]
        );$fieldset->addField(
            'lockset',
            'text',
            ['name' => 'lockset', 'label' => __('Lock lockset'), 'title' => __('Lock lockset')]
        );$fieldset->addField(
            'cylinder',
            'text',
            ['name' => 'cylinder', 'label' => __('Lock cylinder'), 'title' => __('Lock cylinder')]
        );$fieldset->addField(
            'logo',
            'image',
            ['name' => 'logo', 'label' => __('Lock logo'), 'title' => __('Lock logo')]
        );$fieldset->addField(
            'before_image1',
            'image',
            ['name' => 'before_image1', 'label' => __('Lock before_image1'), 'title' => __('Lock before_image1')]
        );$fieldset->addField(
            'before_image2',
            'image',
            ['name' => 'before_image2', 'label' => __('Lock before_image2'), 'title' => __('Lock before_image2')]
        );$fieldset->addField(
            'after_image1',
            'image',
            ['name' => 'after_image1', 'label' => __('Lock after_image1'), 'title' => __('Lock after_image1')]
        );$fieldset->addField(
            'after_image2',
            'image',
            ['name' => 'after_image2', 'label' => __('Lock after_image2'), 'title' => __('Lock after_image2'),
                'note' => 'Allow image type: jpg, jpeg, gif, png']
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
            'district_id',
            'select',
            [
                'label' => __('District'),
                'title' => __('District'),
                'name' => 'district_id',
                'required' => true,
                'options' => $this->_getDistrictOptions()
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
     * Get dropdown options for district
     *
     * @return array
     */
    protected function _getDistrictOptions() {
        $availableOptions = $this->_districtSource->toOptionArray();
        $options = [];
        foreach($availableOptions as $availableOption) {
            $options[$availableOption['value']] = __($availableOption['label']);
        }
        return $options;
    }
}
