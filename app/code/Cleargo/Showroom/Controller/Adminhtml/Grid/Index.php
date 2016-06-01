<?php
namespace Cleargo\Showroom\Controller\Adminhtml\Grid;


class Index extends  \Magento\Backend\App\Action

{
    const ADMIN_RESOURCE = 'Cleargo_Showroom::add_row';

    protected $resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ){
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Map Management'));

        $resultPage->setActiveMenu('Cleargo_Showroom::add_row');
        $resultPage->addBreadcrumb(__('Jobs'), __('Jobs'));
        $resultPage->addBreadcrumb(__('Manage Jobs'), __('Manage Jobs'));
        return $resultPage;
    }
}