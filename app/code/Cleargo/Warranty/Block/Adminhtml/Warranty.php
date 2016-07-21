<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\Warranty\Block\Adminhtml;

/**
 * Adminhtml dealer blocks content block
 */
class Warranty extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Cleargo_Warranty';
        $this->_controller = 'adminhtml_block';
        $this->_headerText = __('Warrantys');
        $this->_addButtonLabel = __('Add New Warranty');
        parent::_construct();
    }
}
