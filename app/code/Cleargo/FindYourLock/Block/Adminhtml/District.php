<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Block\Adminhtml;

/**
 * Adminhtml district blocks content block
 */
class District extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Cleargo_FindYourLock';
        $this->_controller = 'adminhtml_block';
        $this->_headerText = __('Lock Districts');
        $this->_addButtonLabel = __('Add New District');
        parent::_construct();
    }
}
