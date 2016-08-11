<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
namespace Manadev\Core\Blocks\Adminhtml\ExtensionControl\Feature;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\Select;
use Manadev\Core\Model\ExtensionFactory;

class IsEnabledColumn extends Select
{
    protected $loadedExtensions = [];
    /**
     * @var ExtensionFactory
     */
    private $extensionFactory;

    /**
     * @param \Magento\Backend\Block\Context $context
     * @param \Magento\Backend\Block\Widget\Grid\Column\Renderer\Options\Converter $converter
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Backend\Block\Widget\Grid\Column\Renderer\Options\Converter $converter,
        ExtensionFactory $extensionFactory,
        array $data = []
    ) {
        $this->extensionFactory = $extensionFactory;
        parent::__construct($context, $converter, $data);
    }


    public function render(\Magento\Framework\DataObject $row) {
        $html = '<select name="' . ($this->getColumn()->getName() ? $this->getColumn()->getName() : $this->getColumn()->getId()) . '" ' . $this->getColumn()->getValidateClass();

        $html .= (
        ($this->_canDisplayUseDefault() && !$this->_usedDefault($row)) ||
        !is_numeric($row->getData('id'))
            ) ? "disabled='disabled'" : "";

        $html .= " >";
        $value = $row->getData($this->getColumn()->getIndex());

        foreach ($this->getColumn()->getOptions() as $val => $label) {
            $selected = (($val == $value && (!is_null($value))) ? ' selected="selected"' : '');
            $html .= '<option value="' . $val . '"' . $selected . '>' . $label . '</option>';
        }
        $html .= '</select>';

        $html .= '<input name="id" type="checkbox" class="checkbox" checked value="'.$row->getData('id').'" style="display:none;" />';


        if($this->_canDisplayUseDefault()) {
            $elementToggleCode = "toggleValueElements(this, this.parentNode);";
            $html .= "&nbsp; <input ";
            $html .= "type='checkbox' name='use_default[]' class='use-default-control' ";
            $html .= "id='". $row->getData('id') . "_default' ";
            $html .= ($this->_usedDefault($row)) ? "" : "checked='checked' ";
            $html .= !is_numeric($row->getData('id')) ? "style='display:none;' " : "";
            $html .= "onclick='".$elementToggleCode."' value='". $row->getData('id') ."' />";

            $html .= !is_numeric($row->getData('id')) ? "" : "<span class='use-default-label'>". $this->_useDefaultLabel() ."</span>";
        }

        return $html;
    }

    protected function _useDefaultLabel() {
        $data = ($this->getRequest()->getParam('store')) ? 'default_store_label': 'default_label';
        return $this->getColumn()->getData($data);
    }

    protected function _canDisplayUseDefault() {
        $canUseDefault = false;
        if(!$this->getRequest()->getParam('store') && $this->getColumn()->getData('default_label')) {
            $canUseDefault = true;
        }
        if($this->getRequest()->getParam('store') && $this->getColumn()->getData('default_store_label')) {
            $canUseDefault = true;
        }
        return $canUseDefault;
    }

    protected function _usedDefault($row) {
        if(!$this->_canDisplayUseDefault()) {
            return false;
        }

        if(!isset($this->loadedExtensions[$row->getData('module')])) {
            $extension = $this->extensionFactory->create();

            $extension->setData('store_id', $this->_getStore());
            $extension->load($row->getData('title'), 'title');

            $this->loadedExtensions[$row->getData('module')] = $extension->hasId();
        }

        return $this->loadedExtensions[$row->getData('module')];
    }

    protected function _getStore() {
        return $this->getRequest()->getParam('store', 0);
    }

}