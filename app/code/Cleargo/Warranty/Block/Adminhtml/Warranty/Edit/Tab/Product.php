<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\Warranty\Block\Adminhtml\Warranty\Edit\Tab;

/**
 * Cms page edit form main tab
 */
class Product extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
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
        $this->setTitle(__('Product Information'));
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
            ['legend' => __('Product Information'), 'class' => 'fieldset-wide']
        );


    $fieldset->addField(
        'product_type',
        'select',
        [
            'label' => __('Product Type'),
            'title' => __('Product Type'),
            'name' => 'product_type',
            'required' => true,
            'options' => $model->getAvailableProductTypes()
        ]
    );
    $fieldset->addField(
        'serial_num',
        'text',
        ['name' => 'serial_num', 'label' => __('Serial Number'), 'title' => __('Serial Number'), 'required' => true]
    );
        $fieldset->addField(
        'profile',
        'text',
        ['name' => 'profile', 'label' => __('Profile'), 'title' => __('Profile'), 'required' => true]
    );
        $fieldset->addField(
        't_combination',
        'text',
        ['name' => 't_combination', 'label' => __('T Combination'), 'title' => __('T Combination'), 'required' => true]
    );
        $fieldset->addField(
        'fp_combination',
        'text',
        ['name' => 'fp_combination', 'label' => __('FP Combination'), 'title' => __('FP Combination'), 'required' => true]
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
        return __('Product Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Product Information');
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
