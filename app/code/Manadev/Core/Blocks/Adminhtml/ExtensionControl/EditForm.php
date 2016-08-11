<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\Core\Blocks\Adminhtml\ExtensionControl;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Model\PageLayout\Config\BuilderInterface;
use Magento\Store\Model\Store;
use Magento\Theme\Model\Layout\Source\Layout;
use Manadev\Core\Blocks\Adminhtml\Field;
use Manadev\Core\Model\Source\Status;
use Manadev\Sorting\Sources\Attribute;
use Manadev\Sorting\Sources\Direction;

class EditForm extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var Status
     */
    private $statusSourceModel;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        Status $statusSourceModel,
        array $data = []
    ) {
        $this->statusSourceModel = $statusSourceModel;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $form->setHtmlIdPrefix('block_');
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId) {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Preparing global layout
     *
     * You can redefine this method in child classes for changing layout
     *
     * @return $this
     */
    protected function _prepareLayout() {
        $result = parent::_prepareLayout();
        $this->addChild('extensionGrid', 'Manadev\Core\Blocks\Adminhtml\ExtensionControl\Grid');
        return $result;
    }

    /**
     * Processing block html after rendering
     *
     * @param   string $html
     * @return  string
     */
    protected function _afterToHtml($html) {
        $html .= $this->getChildHtml('extensionGrid');
        return parent::_afterToHtml($html);
    }


}