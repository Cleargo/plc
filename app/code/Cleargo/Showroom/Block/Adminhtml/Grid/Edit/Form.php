<?php
namespace Cleargo\Showroom\Block\Adminhtml\Grid\Edit;

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
        /** @var \Cleargo\Showroom\Model\Department $model */
        $model = $this->_coreRegistry->registry('showroom_grid');

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
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
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
$testing = [[
    'label' => "Type A",
    'value' => 1
],[
    'label' => "Type B",
    'value' => 2
],[
    'label' => "Type C",
    'value' => 3
]];

        $field = $fieldset->addField(
            'type_id',
            'multiselect',
            [
                'name' => 'type[]',
                'label' => __('Type'),
                'title' => __('Type'),
                'required' => true,
                'values' => $testing
            ]
        );
        $renderer = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
        );
        $field->setRenderer($renderer);

        $fieldset->addField(
            'address',
            'text',
            ['name' => 'address', 'label' => __('address'), 'title' => __('address'), 'required' => true]
        );

        $fieldset->addField(
            'xcoordinates',
            'text',
            ['name' => 'xcoordinates', 'label' => __('xcoordinates'), 'title' => __('xcoordinates'), 'required' => true]
        );
        $fieldset->addField(
            'ycoordinates',
            'text',
            ['name' => 'ycoordinates', 'label' => __('ycoordinates'), 'title' => __('ycoordinates'), 'required' => true]
        );

        $fieldset->addField(
            'telephone',
            'text',
            ['name' => 'telephone', 'label' => __('telephone'), 'title' => __('telephone'), 'required' => true]
        );
         $fieldset->addField(
            'whatsapp',
            'text',
            ['name' => 'whatsapp', 'label' => __('whatsapp'), 'title' => __('whatsapp'), 'required' => true]
        );



        $fieldset->addField(
            'office_hour',
            'text',
            ['name' => 'office_hour', 'label' => __('office_hour'), 'title' => __('office_hour'), 'required' => true]
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