<?php
namespace Cleargo\Contactus\Block\Adminhtml\Grid\Edit;

use \Magento\Backend\Block\Widget\Form\Generic;

class Form extends Generic
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

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
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
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
        $this->setId('grid_form');
        $this->setTitle(__('Department Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Cleargo\Contactus\Model\Department $model */
        $model = $this->_coreRegistry->registry('contactus_grid');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $form->setHtmlIdPrefix('grid_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getId()) {
            $fieldset->addField('location_id', 'hidden', ['name' => 'location_id']);
        }

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
            'title',
            'text',
            ['name' => 'title', 'label' => __('Title'), 'title' => __('Title'), 'required' => true]
        );

        $fieldset->addField(
            'address',
            'text',
            ['name' => 'address', 'label' => __('Address'), 'title' => __('Address'), 'required' => true]
        );

        $fieldset->addField(
            'xcoordinates',
            'text',
            ['name' => 'xcoordinates', 'label' => __('X coordinate'), 'title' => __('X coordinate'), 'required' => true]
        );
        $fieldset->addField(
            'ycoordinates',
            'text',
            ['name' => 'ycoordinates', 'label' => __('Y coordinate'), 'title' => __('Y coordinate'), 'required' => true]
        );
        $fieldset->addField(
            'sort_order',
            'text',
            ['name' => 'sort_order', 'label' => __('Sort Order'), 'title' => __('Sort Order'), 'required' => true]
        );

        $fieldset->addField(
            'telephone',
            'text',
            ['name' => 'telephone', 'label' => __('Telephone'), 'title' => __('Telephone'), 'required' => true]
        );
         $fieldset->addField(
            'fax',
            'text',
            ['name' => 'fax', 'label' => __('Fax'), 'title' => __('Fax'), 'required' => false]
        );

        $fieldset->addField(
            'email',
            'text',
            ['name' => 'email', 'label' => __('Email'), 'title' => __('Email'), 'required' => false]
        );

 $fieldset->addField(
            'office_hour',
            'textarea',
            ['name' => 'office_hour', 'label' => __('Office hour'), 'title' => __('Office hour'), 'required' => false]
        );

 $fieldset->addField(
            'lunch_time',
            'textarea',
            ['name' => 'lunch_time', 'label' => __('Lunch time'), 'title' => __('Lunch time'), 'required' => false]
        );




/*
        $fieldset->addField(
            'description',
            'textarea',
            ['name' => 'description', 'label' => __('Department Description'), 'title' => __('Department Description'), 'required' => true]
        );*/

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}