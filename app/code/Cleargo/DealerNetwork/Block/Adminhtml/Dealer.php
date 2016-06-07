<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Block\Adminhtml;

/**
 * Adminhtml dealer blocks content block
 */
class Dealer extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Cleargo_DealerNetwork';
        $this->_controller = 'adminhtml_block';
        $this->_headerText = __('Dealer Dealers');
        $this->_addButtonLabel = __('Add New Dealer');
        parent::_construct();
    }
}
