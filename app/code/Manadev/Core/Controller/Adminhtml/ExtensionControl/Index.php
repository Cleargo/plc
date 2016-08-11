<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
namespace Manadev\Core\Controller\Adminhtml\ExtensionControl;

use Magento\Backend\App\AbstractAction;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Manadev\Core\Resources\ExtensionCollectionFactory;

class Index extends AbstractAction
{
    /**
     * @var PageFactory
     */
    private $resultPageFactory;
    /**
     * @var ExtensionCollectionFactory
     */
    private $extensionCollectionFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param StoreManagerInterface $storeManager
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ExtensionCollectionFactory $extensionCollectionFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->extensionCollectionFactory = $extensionCollectionFactory;
    }

    /**
     * @return Page\Interceptor
     */
    public function execute()
    {
        $collection = $this->extensionCollectionFactory->create();
        $collection->setStore(0)->addFieldToFilter('is_pending', ['eq'=>'1']);
        if($collection->count()) {
            $this->messageManager->addNotice('Please run command `bin/magento mana:update`.');
        }
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Manadev_Core::extension_control');
        $resultPage->getConfig()->getTitle()->prepend((__('Installed MANAdev Extensions')));
        return $resultPage;
    }
}
