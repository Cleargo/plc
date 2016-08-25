<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 8/8/2016
 * Time: 6:20 PM
 */
namespace Cleargo\CustomOption\Model\ResourceModel\Product;

use Magento\Catalog\Model\ResourceModel\Product\Option as OriginalResourceModel;

class Option extends OriginalResourceModel{

    /**
     * @var \Magento\Framework\EntityManager\MetadataPool
     */
    protected $metadataPool;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Currency factory
     *
     * @var \Magento\Directory\Model\CurrencyFactory
     */
    protected $_currencyFactory;

    /**
     * Core config model
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_config;
    protected $adapterFactory;
    protected $uploader;
    protected $filesystem;

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\Image\AdapterFactory $adapterFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploader,
        \Magento\Framework\Filesystem $filesystem,
        $connectionName = null
    ) {
        $this->adapterFactory = $adapterFactory;
        $this->uploader = $uploader;
        $this->filesystem = $filesystem;
        parent::__construct($context, $currencyFactory,$storeManager,$config, $connectionName);
    }


    protected function mapImageToOption($fieldArr,$data){
        if(gettype($fieldArr) != "array"){
            $fieldArr = [$fieldArr];
        }
        foreach ($fieldArr as $field){
            if($data['record_id'] == $_FILES[$field]['record_id']){
                if(isset($_FILES[$field]['values']))  {
                    foreach ($_FILES[$field]['values'] as $vKey => $value ){
                        $_FILES[$field]['values'][$vKey]['option_id'] = $data['option_id'];
                        $_FILES['value_'.$data['option_id'].$_FILES[$field]['values'][$vKey]['sort_order']]  =  $_FILES[$field]['values'][$vKey];
                    }
                }
            }

            if (
                isset($_FILES[$field])
                && isset($_FILES[$field]['name'])
                && strlen($_FILES[$field]['name'])
            ) {
                try {
                    if($data['record_id'] == $_FILES[$field]['record_id']){
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
                        $data['image'] = $base_media_path.$result['file'];
                    }

                } catch (\Exception $e) {
                    if ($e->getCode() == 0) {
                        var_dump($e->getMessage());
                    }
                }
            } else {
                if (isset($data[$field]) && isset($data[$field]['value'])) {
                    if ($_FILES[$field]['delete_image']) {
                        $data['image'] = null;
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
     * Save options store data
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return \Magento\Framework\Model\ResourceModel\Db\AbstractDb
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {

        $data = $object->getData();
        if(isset($data['image'])){
            unset($data['image']);
        }

        $temp = [];
        foreach($_FILES as $key => $file){



            if (strpos($key, 'old_option_') !== false || strpos($key, 'new_option_') !== false ) {//



                if(!isset($temp['image'])){
                    $temp = $this->mapImageToOption($key,$data);

                    //break;
                }
                if(isset($temp['image'])){
                    var_dump($temp['image']);
                }
            }
        };
        if(isset($temp['image'])){
            $object->setData($temp);
        }

        $finalData = $object->getData();

        $this->_saveValuePrices($object);
        $this->_saveValueTitles($object);
        $this->_saveValueImages($object);
        return parent::_afterSave($object);
    }
    
        
    protected function _saveValueImages(\Magento\Framework\Model\AbstractModel $object)
    {
        $connection = $this->getConnection();
        $imageTableName = $this->getTable('catalog_product_option_image');
        foreach ([\Magento\Store\Model\Store::DEFAULT_STORE_ID, $object->getStoreId()] as $storeId) {
            $existInCurrentStore = $this->getColFromOptionTable($imageTableName, (int)$object->getId(), (int)$storeId);
            $existInDefaultStore = $this->getColFromOptionTable(
                $imageTableName,
                (int)$object->getId(),
                \Magento\Store\Model\Store::DEFAULT_STORE_ID
            );
            if ($object->getImage() && !$object->getDeleteImage()) {
                if ($existInCurrentStore) {
                    if ($object->getStoreId() == $storeId) {
                        $data = $this->_prepareDataForTable(
                            new \Magento\Framework\DataObject(['image' => $object->getImage()]),
                            $imageTableName
                        );
                        $connection->update(
                            $imageTableName,
                            $data,
                            [
                                'option_id = ?' => $object->getId(),
                                'store_id  = ?' => $storeId,
                            ]
                        );
                    }
                } else {
                    // we should insert record into not default store only of if it does not exist in default store
                    if (($storeId == \Magento\Store\Model\Store::DEFAULT_STORE_ID && !$existInDefaultStore)
                        || ($storeId != \Magento\Store\Model\Store::DEFAULT_STORE_ID && !$existInCurrentStore)
                    ) {
                        $data = $this->_prepareDataForTable(
                            new \Magento\Framework\DataObject(
                                [
                                    'option_id' => $object->getId(),
                                    'store_id' => $storeId,
                                    'image' => $object->getImage(),
                                ]
                            ),
                            $imageTableName
                        );
                        $connection->insert($imageTableName, $data);
                    }
                }
            } else {
                if ($object->getId() && $object->getStoreId() > \Magento\Store\Model\Store::DEFAULT_STORE_ID
                    && $storeId
                ) {
                    $connection->delete(
                        $imageTableName,
                        [
                            'option_id = ?' => $object->getId(),
                            'store_id  = ?' => $object->getStoreId(),
                        ]
                    );
                }
            }
        }
    }
}