<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\Warranty\Block\Adminhtml\Warranty\Edit\Tab;

/**
 * Cms page edit form main tab
 */
class Purchase extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Cleargo\Warranty\Model\Warranty\Source\District
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
        $this->setTitle(__('Warranty Information'));
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
            ['legend' => __('Purchase Information'), 'class' => 'fieldset-wide']
        );


        $fieldset->addField(
            'date_of_purchase',
            'date',
            ['name' => 'date_of_purchase',
                'label' => __('Date'),
                'title' => __('Date'),
                'required' => true,
                'date_format' => $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT),
            ]
        );$fieldset->addField(
            'name_of_dealer',
            'text',
            ['name' => 'name_of_dealer', 'label' => __('Name of Dealer'), 'title' => __('Name of Dealer'), 'required' => true]
        );$fieldset->addField(
            'invoice_no',
            'text',
            ['name' => 'invoice_no', 'label' => __(' Invoice No.'), 'title' => __(' Invoice No.')]
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
        return __('Purchase Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Purchase Information');
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
