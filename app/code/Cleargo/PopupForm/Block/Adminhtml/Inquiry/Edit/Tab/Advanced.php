<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\PopupForm\Block\Adminhtml\Inquiry\Edit\Tab;

/**
 * Cms page edit form main tab
 */
class Advanced extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
    protected $_optionCollection;

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
        \Cleargo\PopupForm\Model\ResourceModel\Option\Collection $_optionCollection,
        array $data = []
    ) {
        $this->_optionCollection = $_optionCollection;
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
        /* @var $model \Cleargo\PopupForm\Model\Inquiry */
        $model = $this->_coreRegistry->registry('inquiry_inquiry');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('inquiry_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Content Information'), 'class' => 'fieldset-wide']
        );


        $fieldset->addField(
            'content',
            'textarea',
            ['name' => 'content', 'label' => __('Content'), 'title' => __('Content'), 'required' => true]
        );

        $fieldset->addField(
            'question_type_id',
            'multiselect',
            [
                'label' => __('Question Type'),
                'title' => __('Question Type'),
                'name' => 'question_type_id',
                'required' => true,
                'values' =>  $this->_optionCollection->toOptionArray()
            ]
        );

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    protected function _getOptions() {
        $availableOptions = $this->_optionCollection->toOptionArray();
        $options = [];
        foreach($availableOptions as $availableOption) {
            $options[$availableOption['value']] = __($availableOption['label']);
        }
        var_dump($options);
        return $options;
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
