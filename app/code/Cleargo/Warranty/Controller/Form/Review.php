<?php
/**
 *
 * Copyright ? 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\Warranty\Controller\Form;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\PageFactory;

class Review extends Action
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
       $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * View Dealer List
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        $post = $this->getRequest()->getPostValue();


       if (!$post) {
           $this->_redirect('*/*/');
            return;
        }
        /** @var \Magento\Framework\View\Result\Page $resultPage */

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getLayout()->initMessages();
        $this->_eventManager->dispatch('warranty_form_indexpost');
        return $resultPage;
    }
}
