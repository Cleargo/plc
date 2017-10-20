<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\FindYourLock\Block\Adminhtml\Lock\Edit\Tab;

/**
 * Cms page edit form main tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
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
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getLockId()) {
            $fieldset->addField('lock_id', 'hidden', ['name' => 'lock_id']);
        }

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

        $fieldset->addField(
            'name',
            'text',
            ['name' => 'name', 'label' => __('Lock Name'), 'title' => __('Lock Name'), 'required' => true]
        );

        $fieldset->addField(
            'name2',
            'text',
            ['name' => 'name2', 'label' => __('Lock Name2'), 'title' => __('Lock Name2'), 'required' => false]
        );
        $fieldset->addField(
            'address',
            'text',
            ['name' => 'address', 'label' => __('Lock Address'), 'title' => __('Lock Address'), 'required' => true]
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
        return __('Lock Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Lock Information');
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
