<?php
/**
 *
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Controller\Adminhtml\District;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    /**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Cleargo_FindYourLock::district');
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Cleargo_FindYourLock::district');
        $resultPage->addBreadcrumb(__('Find Your Lock'), __('Find Your Lock'));
        $resultPage->addBreadcrumb(__('Manage Districts'), __('Manage Districts'));
        $resultPage->getConfig()->getTitle()->prepend(__('Districts'));

        return $resultPage;
    }
}
