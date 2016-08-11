<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
namespace Manadev\Core\Blocks\Adminhtml\ExtensionControl\Feature;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\Text;

class ExtensionNameColumn extends Text
{
    /**
     * @param \Magento\Backend\Block\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function render(\Magento\Framework\DataObject $row) {
        $html = parent::render($row);
        if(!$row->getData('is_extension')) {
            $html = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$html;
        }

        return $html;
    }
}