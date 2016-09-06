<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\CustomOption\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Catalog\Controller\Adminhtml\Product;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class Save
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Save extends \Magento\Catalog\Controller\Adminhtml\Product
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
     * @var \Magento\Catalog\Api\CategoryLinkManagementInterface
     */
    protected $categoryLinkManagement;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

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
    protected $option;

    /**
     * Save constructor.
     *
     * @param Action\Context $context
     * @param Builder $productBuilder
     * @param Initialization\Helper $initializationHelper
     * @param \Magento\Catalog\Model\Product\Copier $productCopier
     * @param \Magento\Catalog\Model\Product\TypeTransitionManager $productTypeManager
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Catalog\Controller\Adminhtml\Product\Builder $productBuilder,
        \Magento\Catalog\Controller\Adminhtml\Product\Initialization\Helper $initializationHelper,
        \Magento\Catalog\Model\Product\Copier $productCopier,
        \Magento\Catalog\Model\Product\TypeTransitionManager $productTypeManager,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Cleargo\CustomOption\Model\ResourceModel\Product\Option $option,
        \Magento\Framework\Image\AdapterFactory $adapterFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploader,
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->initializationHelper = $initializationHelper;
        $this->productCopier = $productCopier;
        $this->productTypeManager = $productTypeManager;
        $this->option = $option;
        $this->productRepository = $productRepository;
        $this->adapterFactory = $adapterFactory;
        $this->uploader = $uploader;
        $this->filesystem = $filesystem;
        parent::__construct($context, $productBuilder);
    }


    protected function transferFilesArr ($input , $optData){

        $returnArr = [];
        foreach($_FILES['product']['name']['options']  as $key =>$image){
            if($_FILES['product']['tmp_name']['options'][$key]["image"] == ''){
                $_FILES['product']['tmp_name']['options'][$key]["image"] ='123';
            }
            $returnArr[$key] =  array(
                'name' => $_FILES['product']['name']['options'][$key]["image"],
                'type' => $_FILES['product']['type']['options'][$key]["image"],
                'tmp_name' => $_FILES['product']['tmp_name']['options'][$key]["image"],
                'error' => $_FILES['product']['error']['options'][$key]["image"],
                'size' => $_FILES['product']['size']['options'][$key]["image"]
            );

            $returnArr[$key]['delete_image'] = $optData[$key]['delete_image'];


            if(
                isset($_FILES['product']['name']['options'][$key]['values'])
            ){
                //$returnArr[$key]['values'] = $_FILES['product']['name']['options'][$key]['values'];

                $valueArr = [] ;
                foreach ($_FILES['product']['name']['options'][$key]['values'] as $key2 => $value2 ){
                    $valueArr[$key2] = array(
                        'name' => $_FILES['product']['name']['options'][$key]["values"][$key2]["image"],
                        'type' => $_FILES['product']['type']['options'][$key]["values"][$key2]["image"],
                        'tmp_name' => $_FILES['product']['tmp_name']['options'][$key]["values"][$key2]["image"],
                        'error' => $_FILES['product']['error']['options'][$key]["values"][$key2]["image"],
                        'size' => $_FILES['product']['size']['options'][$key]["values"][$key2]["image"],
                        'parent_record_id' =>  $key,
                        'sort_order' => $key2 + 1
                    );
                    $returnArr[$key]['values'][$key2]= $valueArr[$key2];
                }
            }

            if(isset($optData[$key]['option_id']) && $optData[$key]['option_id'] != '' ){
                $returnArr[$key]['record_id'] = $optData[$key]['record_id'];
                $_FILES[ 'old_option_'.$optData[$key]['option_id'] ] = $returnArr[$key];
            } else {
                $returnArr[$key]['record_id'] = $optData[$key]['record_id'];
                $_FILES['new_option_'.$key] = $returnArr[$key];
            }

        }
        unset($_FILES['product']);
    }

    protected function mapImageToOption($fieldArr,$data){
        if(gettype($fieldArr) != "array"){
            $fieldArr = [$fieldArr];
        }
        foreach ($fieldArr as $field){
            if (isset($_FILES[$field])
                && isset($_FILES[$field]['name'])
                && strlen($_FILES[$field]['name'])) {

                try {
                    $base_media_path = 'cleargo/product/options';
                    $uploader = $this->uploader->create(
                        ['fileId' => $field]
                    );
                    $uploader->setAllowedExtensions(['pdf','csv','jpg','png','gif']);
                    //$imageAdapter = $this->adapterFactory->create();
                    //$uploader->addValidateCallback($field, $imageAdapter, 'validateUploadFile');
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
                    $mediaDirectory = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                    $result = $uploader->save(
                        $mediaDirectory->getAbsolutePath($base_media_path)
                    );
                    $data[$_FILES[$field]['record_id']]['image'] = $base_media_path.$result['file'];
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
        $storeId = $this->getRequest()->getParam('store', 0);
        $store = $this->getStoreManager()->getStore($storeId);
        $this->getStoreManager()->setCurrentStore($store->getCode());
        $redirectBack = $this->getRequest()->getParam('back', false);
        $productId = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        $productAttributeSetId = $this->getRequest()->getParam('set');
        $productTypeId = $this->getRequest()->getParam('type');
        if ($data) {
            try {
                if(isset($_FILES['product'])&& isset($data['product']['options'] ) ){
                    $this->transferFilesArr($_FILES['product'], $data['product']['options'] );
                }

                $tempRequest = $this->getRequest()->getParams();
                if(isset($data['product']['options'])){
                    $tempRequest['options'] = $data['product']['options'];
                    $this->getRequest()->setParams($tempRequest);
                }
                $product = $this->initializationHelper->initialize(
                    $this->productBuilder->build($this->getRequest())
                );
                //$product->setData($data);
                $this->productTypeManager->processProduct($product);

                if (isset($data['product'][$product->getIdFieldName()])) {
                    throw new \Magento\Framework\Exception\LocalizedException(__('Unable to save product'));
                }
                $originalSku = $product->getSku();

                $product->save();

                // finish product save
                //die();
                $this->handleImageRemoveError($data, $product->getId());
                $this->getCategoryLinkManagement()->assignProductToCategories(
                    $product->getSku(),
                    $product->getCategoryIds()
                );
                $productId = $product->getEntityId();
                $productAttributeSetId = $product->getAttributeSetId();
                $productTypeId = $product->getTypeId();

                $this->copyToStores($data, $productId);

                $this->messageManager->addSuccess(__('You saved the product.'));
                $this->getDataPersistor()->clear('catalog_product');
                if ($product->getSku() != $originalSku) {
                    $this->messageManager->addNotice(
                        __(
                            'SKU for product %1 has been changed to %2.',
                            $this->_objectManager->get('Magento\Framework\Escaper')->escapeHtml($product->getName()),
                            $this->_objectManager->get('Magento\Framework\Escaper')->escapeHtml($product->getSku())
                        )
                    );
                }
                $this->_eventManager->dispatch(
                    'controller_action_catalog_product_save_entity_after',
                    ['controller' => $this, 'product' => $product]
                );

                if ($redirectBack === 'duplicate') {
                    $newProduct = $this->productCopier->copy($product);
                    $this->messageManager->addSuccess(__('You duplicated the product.'));
                }
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $this->getDataPersistor()->set('catalog_product', $data);
                $redirectBack = $productId ? true : 'new';
            } catch (\Exception $e) {
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->messageManager->addError($e->getMessage());
                $this->getDataPersistor()->set('catalog_product', $data);
                $redirectBack = $productId ? true : 'new';
            }
        } else {
            $resultRedirect->setPath('catalog/*/', ['store' => $storeId]);
            $this->messageManager->addError('No data to save');
            return $resultRedirect;
        }

        if ($redirectBack === 'new') {
            $resultRedirect->setPath(
                'catalog/*/new',
                ['set' => $productAttributeSetId, 'type' => $productTypeId]
            );
        } elseif ($redirectBack === 'duplicate' && isset($newProduct)) {
            $resultRedirect->setPath(
                'catalog/*/edit',
                ['id' => $newProduct->getEntityId(), 'back' => null, '_current' => true]
            );
        } elseif ($redirectBack) {
            $resultRedirect->setPath(
                'catalog/*/edit',
                ['id' => $productId, '_current' => true, 'set' => $productAttributeSetId]
            );
        } else {
            $resultRedirect->setPath('catalog/*/', ['store' => $storeId]);
        }
        return $resultRedirect;
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

    /**
     * Do copying data to stores
     *
     * @param array $data
     * @param int $productId
     * @return void
     */
    protected function copyToStores($data, $productId)
    {
        if (!empty($data['product']['copy_to_stores'])) {
            foreach ($data['product']['copy_to_stores'] as $websiteId => $group) {
                if (isset($data['product']['website_ids'][$websiteId])
                    && (bool)$data['product']['website_ids'][$websiteId]) {
                    foreach ($group as $store) {
                        $copyFrom = (isset($store['copy_from'])) ? $store['copy_from'] : 0;
                        $copyTo = (isset($store['copy_to'])) ? $store['copy_to'] : 0;
                        if ($copyTo) {
                            $this->_objectManager->create('Magento\Catalog\Model\Product')
                                ->setStoreId($copyFrom)
                                ->load($productId)
                                ->setStoreId($copyTo)
                                ->setCopyFromView(true)
                                ->save();
                        }
                    }
                }
            }
        }
    }

    /**
     * @return \Magento\Catalog\Api\CategoryLinkManagementInterface
     */
    private function getCategoryLinkManagement()
    {
        if (null === $this->categoryLinkManagement) {
            $this->categoryLinkManagement = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('Magento\Catalog\Api\CategoryLinkManagementInterface');
        }
        return $this->categoryLinkManagement;
    }

    /**
     * @return StoreManagerInterface
     * @deprecated
     */
    private function getStoreManager()
    {
        if (null === $this->storeManager) {
            $this->storeManager = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('Magento\Store\Model\StoreManagerInterface');
        }
        return $this->storeManager;
    }

    /**
     * Retrieve data persistor
     *
     * @return DataPersistorInterface|mixed
     * @deprecated
     */
    protected function getDataPersistor()
    {
        if (null === $this->dataPersistor) {
            $this->dataPersistor = $this->_objectManager->get(DataPersistorInterface::class);
        }

        return $this->dataPersistor;
    }
}
