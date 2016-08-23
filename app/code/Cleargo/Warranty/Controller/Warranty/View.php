<?php
/**
 *
 * Copyright ? 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\Warranty\Controller\Warranty;

use Magento\Framework\App\Action\Context;
use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\View\Result\PageFactory;

class View extends AbstractAccount
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
        /** @var \Magento\Framework\View\Result\Page $resultPage */

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getLayout()->initMessages();
        return $resultPage;
    }
}
