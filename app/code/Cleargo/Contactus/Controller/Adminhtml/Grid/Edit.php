<?php
namespace Cleargo\Contactus\Controller\Adminhtml\Grid;

use Magento\Backend\App\Action;

class Edit extends Action
{ 
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var \Cleargo\Contactus\Model\Grid
     */
    protected $_model;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Cleargo\Contactus\Model\Grid $model
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Cleargo\Contactus\Model\Grid $model
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->_model = $model;
        parent::__construct($context);

    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Cleargo_Contactus::grid_save');
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        /*$resultPage->setActiveMenu('Cleargo_Contactus::grid')
            ->addBreadcrumb(__('Grid'), __('Grid'))
            ->addBreadcrumb(__('Manage Grids'), __('Manage Grids'));*/
        return $resultPage;
    }

    /**
     * Edit Grid
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_model;

        // If you have got an id, it's edition
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This grid not exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('contactus_grid', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Grid') : __('New Grid'),
            $id ? __('Edit Grid') : __('New Grid')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Grids'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getName() : __('New Grid'));

        return $resultPage;
    }
}