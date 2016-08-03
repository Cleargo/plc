<?php
/**
 *
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\PopupForm\Controller\Adminhtml\Option;

use Magento\Backend\App\Action;

class Save extends \Magento\Backend\App\Action
{
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Cleargo_PopupForm::option_save');
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {

            $model = $this->_objectManager->create('Cleargo\PopupForm\Model\Option');

            $id = $data["question_type_id"];
            if ($id) {
                $model->load($id);
            }
            if(isset($data ["trans_label"])){
                $data ["trans_label"] = json_encode($data ["trans_label"]);
            }
            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved this option.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['question_type_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                //$this->messageManager->addException($e, __('Something went wrong while saving the option.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['question_type_id' => $this->getRequest()->getParam('question_type_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
