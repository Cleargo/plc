<?php
/**
 *
 * Copyright ? 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\Warranty\Controller\Form;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session;

class Thank extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    protected $customerSession;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }

    /**
     * View Dealer List
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        if (!$this->customerSession->getIsFromWarrantyPost()){
            $this->_redirect('mt5_registration');
        }
        $this->customerSession->setIsFromWarrantyPost(null);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getLayout()->initMessages();
        return $resultPage;
    }
}
