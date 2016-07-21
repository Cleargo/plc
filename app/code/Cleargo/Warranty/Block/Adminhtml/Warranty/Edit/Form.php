<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\Warranty\Block\Adminhtml\Warranty\Edit;

/**
 * Adminhtml lock lock edit form
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Cleargo\Warranty\Model\Warranty\Source\District
     */


    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('warranty_form');
        $this->setTitle(__('Warranty Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post','enctype' => 'multipart/form-data']]
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }


}
