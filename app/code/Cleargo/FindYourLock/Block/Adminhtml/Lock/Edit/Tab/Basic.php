<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\FindYourLock\Block\Adminhtml\Lock\Edit\Tab;

/**
 * Cms page edit form main tab
 */
class Basic extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
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
        //$this->setId('lock_form');
        $this->setTitle(__('Lock Information'));
    }
    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \Cleargo\FindYourLock\Model\Lock */
        $model = $this->_coreRegistry->registry('lock_lock');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('lock_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Basic Information'), 'class' => 'fieldset-wide']
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
        'phrase',
        'text',
        ['name' => 'phrase', 'label' => __('Lock phrase'), 'title' => __('Lock phrase')]
    );$fieldset->addField(
        'thickness',
        'text',
        ['name' => 'thickness', 'label' => __('Lock thickness'), 'title' => __('Lock thickness')]
    );$fieldset->addField(
        'backset',
        'text',
        ['name' => 'backset', 'label' => __('Lock backset'), 'title' => __('Lock backset')]
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
        return __('Basic Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Basic Information');
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
