<?php
/**
 *
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\Warranty\Controller\Adminhtml\Warranty;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Cleargo_Warranty::warranty_delete');
    }

    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('warranty_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $title = "";
            try {
                // init model and delete
                $model = $this->_objectManager->create('Cleargo\Warranty\Model\Warranty');
                $model->load($id);
                $title = $model->getTitle();
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('The warranty has been deleted.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['warranty_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a warranty to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
