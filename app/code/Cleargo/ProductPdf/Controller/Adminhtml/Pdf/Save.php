<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\ProductPdf\Controller\Adminhtml\Pdf;

use Magento\Backend\App\Action;
use Magento\Catalog\Controller\Adminhtml\Product;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var Initialization\Helper
     */
    protected $initializationHelper;

    /**
     * @var \Magento\Catalog\Model\Product\Copier
     */
    protected $productCopier;

    /**
     * @var \Magento\Catalog\Model\Product\TypeTransitionManager
     */
    protected $productTypeManager;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

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
    protected $resultJsonFactory;
    protected $_pdfRepository;



    /**
     * @param Action\Context $context
     * @param Builder $productBuilder
     * @param Initialization\Helper $initializationHelper
     * @param \Magento\Catalog\Model\Product\Copier $productCopier
     * @param \Magento\Catalog\Model\Product\TypeTransitionManager $productTypeManager
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        Product\Builder $productBuilder,
        \Magento\Catalog\Controller\Adminhtml\Product\Initialization\Helper $initializationHelper,
        \Magento\Catalog\Model\Product\Copier $productCopier,
        \Magento\Catalog\Model\Product\TypeTransitionManager $productTypeManager,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Image\AdapterFactory $adapterFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploader,
        \Cleargo\ProductPdf\Model\PdfRepository $_pdfRepository,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {

        $this->initializationHelper = $initializationHelper;
        $this->productCopier = $productCopier;
        $this->productTypeManager = $productTypeManager;
        $this->productRepository = $productRepository;
        $this->adapterFactory = $adapterFactory;
        $this->uploader = $uploader;
        $this->_pdfRepository = $_pdfRepository;
        $this->filesystem = $filesystem;;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }


    /*
     * save product pdf to a flat table
     *
     *
     * */
    protected function saveProductPdf($data){

        if(is_string($data)){
            $temp['value'] = $data;
            $data = $temp;
        }

        if(isset($data['delete'])){
            if($data['delete'] == "true"){
                $this->_pdfRepository->deleteById($data['pdf_id']);
                return;
            }
        }


        if(isset($data['pdf_path'])){
            $pdfRecord = [
                'linked_product_id' => $this->getRequest()->getParam('id'),
                'is_active' => 1

            ];

            $pdfRecord['pdf_path'] = $data['pdf_path'];
            if(isset($data['name'])){
                $pdfRecord['name'] = $data['name'];
            }

            $pdf = $this->_objectManager->create('Cleargo\ProductPdf\Model\Pdf');

            if(isset($data['pdf_id'])&&$data['pdf_id']!=''){ // a pdf record already exists
                //$pdf->load($data['pdf_id']);

                $pdf->load($data['pdf_id']);
                $pdfRecord['pdf_id'] = $data['pdf_id'];
                $originalStores = $pdf->load($data['pdf_id'])->getStores();
                $currentStoreId = $this->getRequest()->getParam('store')? $this->getRequest()->getParam('store'):'0';
                if(!in_array($currentStoreId,$originalStores) ){
                    $originalStores[] = $currentStoreId;
                }

                $pdfRecord['stores'] = $originalStores;

            } else {
                $pdfRecord['stores'] =  $this->getRequest()->getParam('store')? [$this->getRequest()->getParam('store')]:['0'];
            }

            $pdf->setData($pdfRecord);

            try {
                $pdf->save();
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
    }

    protected function transferOldFilesArr ($input){
        $returnArr = [];
        foreach($_FILES['old_pdf']['name']  as $key =>$image){
            $returnArr[$key] =  array(
                'name' => $_FILES['old_pdf']['name'][$key],
                'type' => $_FILES['old_pdf']['type'][$key],
                'tmp_name' => $_FILES['old_pdf']['tmp_name'][$key],
                'error' => $_FILES['old_pdf']['error'][$key],
                'size' => $_FILES['old_pdf']['size'][$key],
                'pdf_id' => $key
            );
            $_FILES['old_pdf_'.$key] = $returnArr[$key];
        }
        unset($_FILES['old_pdf']);

    }
    protected function transferNewFilesArr ($input){
        $returnArr = [];
        foreach($_FILES['new_pdf']['name']  as $key =>$image){
            $returnArr[$key] =  array(
                'name' => $_FILES['new_pdf']['name'][$key],
                'type' => $_FILES['new_pdf']['type'][$key],
                'tmp_name' => $_FILES['new_pdf']['tmp_name'][$key],
                'error' => $_FILES['new_pdf']['error'][$key],
                'size' => $_FILES['new_pdf']['size'][$key]
            );
            $_FILES['new_pdf_'.$key] = $returnArr[$key];
        }
        unset($_FILES['new_pdf']);

    }

    protected function mapPdfToField($fieldArr,$data){




        if(gettype($fieldArr) != "array"){
            $fieldArr = [$fieldArr];
        }
        foreach ($fieldArr as $field){
            if (isset($_FILES[$field]) && isset($_FILES[$field]['name']) && strlen($_FILES[$field]['name'])) {
                /*
                * Save image upload
                */

                try {

                    $base_media_path = 'cleargo/product/pdf';
                    $uploader = $this->uploader->create(
                        ['fileId' => $field]
                    );
                    $uploader->setAllowedExtensions(['pdf','csv']);
                    //$imageAdapter = $this->adapterFactory->create();
                    //$uploader->addValidateCallback($field, $imageAdapter, 'validateUploadFile');
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
                    $mediaDirectory = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                    $result = $uploader->save(
                        $mediaDirectory->getAbsolutePath($base_media_path)
                    );
                    if(strpos($field, 'old_pdf_') !== false){
                        $data['old_pdf'][$_FILES[$field]['pdf_id']] = $base_media_path.$result['file'];
                    }
                    if(strpos($field, 'new_pdf_') !== false){
                        $data['new_pdf'][] = $base_media_path.$result['file'];
                    }



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
     * Save product action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        //var_dump($_FILES);die();

        if(isset($_FILES['old_pdf'])){
            $this->transferOldFilesArr($_FILES['old_pdf']);
        }

        if(isset($_FILES['new_pdf'])){
            $this->transferNewFilesArr($_FILES['new_pdf']);
        }
        $data = $this->getRequest()->getPostValue();
        $data['pdf_names'] = array();
        foreach($_FILES as $key => $file){
            if (strpos($key, 'old_pdf_') !== false || strpos($key, 'new_pdf_') !== false ) {//
                $data = $this->mapPdfToField($key,$data);
            }
        };
        foreach ($data as $key => $field){
            if (strpos($key, 'pdf_name_') !== false ) {//
                $data['pdf_names'][] = $field;
            }
        }


        if(isset($data['old_pdf']) ){
            foreach ($data['old_pdf'] as $key => $pdf){
                $pdf['pdf_id'] = $key;
               $this->saveProductPdf($pdf);
            }
        }

        if(isset($data['new_pdf'])){
            foreach ($data['new_pdf'] as $key => $pdf){
                if(is_string($pdf)){
                    $temp = [];
                    $temp['pdf_path'] = $pdf;
                    if(isset($data['pdf_names'][$key])){
                        $temp['name'] = $data['pdf_names'][$key];
                    }
                    $pdf = $temp;
                } else {
                    $pdf['new_pdf'] = $pdf['value'];
                }
                $this->saveProductPdf($pdf);
            }
        }

        $response = [
            'errors' => false,
            'message' => __('Success')
        ];
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);
    }

    /**
     * Notify customer when image was not deleted in specific case.
     * TODO: temporary workaround must be eliminated in MAGETWO-45306
     *
     * @param array $postData
     * @param int $productId
     * @return void
     */
    private function handleImageRemoveError($postData, $productId)
    {
        if (isset($postData['product']['media_gallery']['images'])) {
            $removedImagesAmount = 0;
            foreach ($postData['product']['media_gallery']['images'] as $image) {
                if (!empty($image['removed'])) {
                    $removedImagesAmount++;
                }
            }
            if ($removedImagesAmount) {
                $expectedImagesAmount = count($postData['product']['media_gallery']['images']) - $removedImagesAmount;
                $product = $this->productRepository->getById($productId);
                if ($expectedImagesAmount != count($product->getMediaGallery('images'))) {
                    $this->messageManager->addNotice(
                        __('The image cannot be removed as it has been assigned to the other image role')
                    );
                }
            }
        }
    }
}
