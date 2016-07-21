<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\Warranty\Block\Adminhtml\Warranty\Edit\Tab;

/**
 * Cms page edit form main tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    protected $_isActive;


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
        //$this->setId('lock_form');
        $this->setTitle(__('Customer Information'));
    }
    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \Cleargo\Warranty\Model\Warranty */
        $model = $this->_coreRegistry->registry('warranty_warranty');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('warranty_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getWarrantyId()) {
            $fieldset->addField('warranty_id', 'hidden', ['name' => 'warranty_id']);
        }

        $fieldset->addField(
            'salutation',
            'text',
            ['name' => 'salutation', 'label' => __('Salutation'), 'title' => __('Salutation'), 'required' => true]
        );

        $fieldset->addField(
            'eng_first_name',
            'text',
            ['name' => 'eng_first_name', 'label' => __('English First Name'), 'title' => __('English First Name'), 'required' => true]
        );
        $fieldset->addField(
            'eng_last_name',
            'text',
            ['name' => 'eng_last_name', 'label' => __('English Last Name'), 'title' => __('English Last Name'), 'required' => true]
        );
        $fieldset->addField(
            'chi_first_name',
            'text',
            ['name' => 'chi_first_name', 'label' => __('Chinese First Name'), 'title' => __('Chinese First Name')]
        );
        $fieldset->addField(
            'chi_last_name',
            'text',
            ['name' => 'chi_last_name', 'label' => __('Chinese Last Name'), 'title' => __('Chinese Last Name')]
        );
        $fieldset->addField(
            'contact_one_country_code',
            'text',
            ['name' => 'contact_one_country_code', 'label' => __('Contact No. (1) Country Code'), 'title' => __('Contact No. (1) Country Code'), 'required' => true]
        );
        $fieldset->addField(
            'contact_one_phone',
            'text',
            ['name' => 'contact_one_phone', 'label' => __('Contact No. (1)'), 'title' => __('Contact No. (1)'), 'required' => true]
        );
        $fieldset->addField(
            'contact_two_country_code',
            'text',
            ['name' => 'contact_two_country_code', 'label' => __('Contact No. (2) Country Code'), 'title' => __('Contact No. (2) Country Code')]
        );
        $fieldset->addField(
            'contact_two_phone',
            'text',
            ['name' => 'contact_two_phone', 'label' => __('Contact No. (2)'), 'title' => __('Contact No. (2)')]
        );
        $fieldset->addField(
            'email',
            'text',
            ['name' => 'email', 'label' => __('Email'), 'title' => __('Email')]
        );
        $fieldset->addField(
            'question_type',
            'select',
            [
                'label' => __('Quesetin Type'),
                'title' => __('Quesetin Type'),
                'name' => 'question_type',
                'required' => true,
                'options' => $model->getAvailableQustions()
            ]
        );
        $fieldset->addField(
            'answer',
            'text',
            ['name' => 'answer', 'label' => __('Answer'), 'title' => __('Answer')]
        );
        $fieldset->addField(
            'date_of_birth',
            'date',
            ['name' => 'date_of_birth',
                'label' => __('Birthday'),
                'title' => __('Birthday'),
                'required' => true,
                'date_format' => $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT),
                //'time_format' => $this->_localeDate->getTimeFormat(\IntlDateFormatter::LONG),
            ]
        );




        $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'status',
                'required' => true,
                'options' => $model->getAvailableStatuses()
            ]
        );


        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Customer Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Customer Information');
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
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }


}
