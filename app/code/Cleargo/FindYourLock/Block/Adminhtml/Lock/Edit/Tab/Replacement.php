<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\FindYourLock\Block\Adminhtml\Lock\Edit\Tab;

/**
 * Cms page edit form main tab
 */
class Replacement extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
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
        $this->setTitle(__('Replacement Information'));
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
            ['legend' => __('Replacement Information'), 'class' => 'fieldset-wide']
        );

    $fieldset->addField(
        'lockset',
        'text',
        ['name' => 'lockset', 'label' => __('Lock lockset'), 'title' => __('Lock lockset')]
    );
    $fieldset->addField(
        'cylinder',
        'text',
        ['name' => 'cylinder', 'label' => __('Lock cylinder'), 'title' => __('Lock cylinder')]
    );
    $fieldset->addField(
        'remarks',
        'text',
        ['name' => 'remarks', 'label' => __('Remarks'), 'title' => __('Remarks')]
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
        return __('Replacement Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Replacement Information');
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
