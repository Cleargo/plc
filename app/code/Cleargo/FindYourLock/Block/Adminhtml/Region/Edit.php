<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Block\Adminhtml\Region;

/**
 * CMS block edit form container
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'region_id';
        $this->_blockGroup = 'Cleargo_FindYourLock';
        $this->_controller = 'adminhtml_region';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Region'));
        $this->buttonList->update('delete', 'label', __('Delete Region'));

        $this->buttonList->add(
            'saveandcontinue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form']],
                ]
            ],
            -100
        );
    }

    /**
     * Get edit form container header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('lock_region')->getId()) {
            return __("Edit Region '%1'", $this->escapeHtml($this->_coreRegistry->registry('lock_region')->getTitle()));
        } else {
            return __('New Region');
        }
    }
}
