<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\Core\Blocks\Adminhtml\ExtensionControl;

use Magento\Backend\Block\Widget\Form\Container;
use Manadev\Sorting\Sources\Attribute;

class EditContainer extends Container {

    /**
     * @var string
     */
    protected $_blockGroup = 'Manadev_Core';

    protected function _construct() {
        $this->_objectId = 'id';
        $this->_blockGroup = 'Manadev_Core';
        $this->_controller = 'adminhtml_extensionControl';

        parent::_construct();

        $this->buttonList->remove('reset');
        $this->buttonList->remove('back');
        $this->buttonList->remove('delete');
        if ($this->_isAllowedAction('Manadev_Core::extension_control')) {
            $this->buttonList->update('save', 'label', __('Save Extensions'));
        } else {
            $this->buttonList->remove('save');
        }
    }

    protected function _buildFormClassName() {
        return $this->nameBuilder->buildClassName(
            [$this->_blockGroup, 'Blocks', $this->_controller, $this->_mode . 'Form']
        );
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

    public function getSaveUrl() {
        return $this->getUrl(
            '*/*/save',
            ['_current' => true, 'back' => null, 'id' => $this->getRequest()->getParam('id')]
        );
    }
}