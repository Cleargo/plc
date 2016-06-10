<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Block\Adminhtml;

/**
 * Adminhtml dealer blocks content block
 */
class Lock extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Cleargo_FindYourLock';
        $this->_controller = 'adminhtml_block';
        $this->_headerText = __('Lock Locks');
        $this->_addButtonLabel = __('Add New Lock');
        parent::_construct();
    }
}
