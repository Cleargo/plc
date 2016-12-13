<?php
/**
 *
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Controller\Adminhtml\Lock;

use Magento\Backend\App\Action;

class Save extends \Magento\Backend\App\Action
{
    /**
 * @var \Magento\Framework\Image\AdapterFactory
 */
    protected $adapterFactory;
    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $uploader;
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezoneInterface;

    public function __construct(Action\Context $context,
                                \Magento\Framework\Image\AdapterFactory $adapterFactory,
                                \Magento\MediaStorage\Model\File\UploaderFactory $uploader,
                                \Magento\Framework\Filesystem $filesystem)
    {
        $this->adapterFactory = $adapterFactory;
        $this->uploader = $uploader;
        $this->filesystem = $filesystem;
        parent::__construct($context);
    }
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Cleargo_FindYourLock::lock_save');
    }

    protected function mapImageToField($fieldArr,$data){
        if(gettype($fieldArr) != "array"){
            $fieldArr = [$fieldArr];
        }
        foreach ($fieldArr as $field){
            if (isset($_FILES[$field]) && isset($_FILES[$field]['name']) && strlen($_FILES[$field]['name'])) {
                /*
                * Save image upload
                */
                try {
                    $base_media_path = 'cleargo/findyourlock/images';
                    $uploader = $this->uploader->create(
                        ['fileId' => $field]
                    );
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $imageAdapter = $this->adapterFactory->create();
                    $uploader->addValidateCallback($field, $imageAdapter, 'validateUploadFile');
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
                    $mediaDirectory = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                    $result = $uploader->save(
                        $mediaDirectory->getAbsolutePath($base_media_path)
                    );
                    $data[$field] = $base_media_path.$result['file'];
                    //var_dump($data[$field]);die();
                } catch (\Exception $e) {
                    if ($e->getCode() == 0) {
                        $this->messageManager->addError($e->getMessage());
                    }
                }
            } else {
                if (isset($data[$field]) && isset($data[$field]['value'])) {
                    if (isset($data[$field]['delete'])) {
                        $data[$field] = null;
                        $data['delete_image'] = true;
                    } elseif (isset($data[$field]['value'])) {
                        $data[$field] = $data[$field]['value'];
                    } else {
                        $data[$field] = null;
                    }
                }
            }


        }

        return $data;
    }


    /**
     * deal with product field
     *
     * @return array
     */

    protected function handleProductField($data){
        if(array_key_exists("products",$data)){
            $tempProduct  = explode("&",$data["products"] );
            if(sizeof($tempProduct) ){
                $data ["product_id"] = end($tempProduct);
            }

        }
        return $data;
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
            $model = $this->_objectManager->create('Cleargo\FindYourLock\Model\Lock');


            $id = $this->getRequest()->getParam('lock_id');
            if ($id) {
                $model->load($id);

            }


            $data = $this->mapImageToField(['logo','before_image1','before_image2','after_image1','after_image2'],$data);
            $data = $this->handleProductField($data);


            $model->setData($data);
            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved this lock.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['lock_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the lock.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['lock_id' => $this->getRequest()->getParam('lock_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
