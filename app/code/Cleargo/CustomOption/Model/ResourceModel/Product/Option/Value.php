<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 23/8/2016
 * Time: 1:41 PM
 */
namespace Cleargo\CustomOption\Model\ResourceModel\Product\Option;

Class Value extends \Magento\Catalog\Model\ResourceModel\Product\Option\Value{

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
    protected $request;


    public function __construct(
        \Magento\Framework\App\Request\Http $request,
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
        $this->request = $request;
        parent::__construct($context, $currencyFactory,$storeManager,$config, $connectionName);
    }

    protected function mapImageToOption($fieldArr,$data){
        if(gettype($fieldArr) != "array"){
            $fieldArr = [$fieldArr];
        }
        foreach ($fieldArr as $field){
            if (
                isset($_FILES[$field])
                && isset($_FILES[$field]['name'])
                && strlen($_FILES[$field]['name'])
            ) {
               /* echo '<pre>';
                var_dump($data['option_id'] == $_FILES[$field]['option_id']);
                var_dump($field);
                var_dump($_FILES[$field]);
                var_dump( $data['sort_order'] == $_FILES[$field]['sort_order']);*/
                try {
                    if(
                        $data['option_id'] == $_FILES[$field]['option_id'] &&
                        $data['sort_order'] == $_FILES[$field]['sort_order']
                    ){
                        $base_media_path = 'cleargo/product/options/values';
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

    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $data = $object->getData();
        if(isset($data['image'])){
            unset($data['image']);
        }
        $temp = [];
        foreach($_FILES as $key => $file){
            if (strpos($key, 'value_') !== false ) {
                if(!isset($temp['image'])){
                    $temp = $this->mapImageToOption($key,$data);

                }
                if(isset($temp['image'])){
                    var_dump($temp['image']);
                }
            }
        };
        if(isset($temp['image'])){
            $object->setData($temp);
        }

        if($this->request->getParam('store')){
            $object->setStoreId($this->request->getParam('store'));
        }

        $this->_saveValuePrices($object);
        $this->_saveValueTitles($object);
        $this->_saveValueImage($object);
        $this->_saveValueDescription($object);
        return parent::_afterSave($object);
    }

    /**
     * Save option value description data
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function _saveValueDescription(\Magento\Framework\Model\AbstractModel $object)
    {
        foreach ([\Magento\Store\Model\Store::DEFAULT_STORE_ID, $object->getStoreId()] as $storeId) {
            $descriptionTable = $this->getTable('catalog_product_option_type_description');
            $select = $this->getConnection()->select()->from(
                $descriptionTable,
                ['option_type_id']
            )->where(
                'option_type_id = ?',
                (int)$object->getId()
            )->where(
                'store_id = ?',
                (int)$storeId
            );
            $optionTypeId = $this->getConnection()->fetchOne($select);
            $existInCurrentStore = $this->getOptionIdFromOptionTable($descriptionTable, (int)$object->getId(), (int)$storeId);
            if ($object->getDescription()) {
                if ($existInCurrentStore) {
                    if ($storeId == $object->getStoreId()) {
                        $where = [
                            'option_type_id = ?' => (int)$optionTypeId,
                            'store_id = ?' => $storeId,
                        ];
                        $bind = ['description' => $object->getDescription()];
                        $this->getConnection()->update($descriptionTable, $bind, $where);
                    }
                } else {
                    $existInDefaultStore = $this->getOptionIdFromOptionTable(
                        $descriptionTable,
                        (int)$object->getId(),
                        \Magento\Store\Model\Store::DEFAULT_STORE_ID
                    );
                    // we should insert record into not default store only of if it does not exist in default store
                    if (($storeId == \Magento\Store\Model\Store::DEFAULT_STORE_ID && !$existInDefaultStore)
                        || ($storeId != \Magento\Store\Model\Store::DEFAULT_STORE_ID && !$existInCurrentStore)
                    ) {
                        $bind = [
                            'option_type_id' => (int)$object->getId(),
                            'store_id' => $storeId,
                            'description' => $object->getDescription(),
                        ];
                        $this->getConnection()->insert($descriptionTable, $bind);
                    }
                }
            } else {
                if ($storeId
                    && $optionTypeId
                    && $object->getStoreId() > \Magento\Store\Model\Store::DEFAULT_STORE_ID
                ) {
                    $where = [
                        'option_type_id = ?' => (int)$optionTypeId,
                        'store_id = ?' => $storeId,
                    ];
                    $this->getConnection()->delete($descriptionTable, $where);
                }
            }
        }
    }
    
    /**
     * Save option value image data
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function _saveValueImage(\Magento\Framework\Model\AbstractModel $object)
    {
        foreach ([\Magento\Store\Model\Store::DEFAULT_STORE_ID, $object->getStoreId()] as $storeId) {
            $imageTable = $this->getTable('catalog_product_option_type_image');
            $select = $this->getConnection()->select()->from(
                $imageTable,
                ['option_type_id']
            )->where(
                'option_type_id = ?',
                (int)$object->getId()
            )->where(
                'store_id = ?',
                (int)$storeId
            );
            $optionTypeId = $this->getConnection()->fetchOne($select);
            $existInCurrentStore = $this->getOptionIdFromOptionTable($imageTable, (int)$object->getId(), (int)$storeId);
            if ($object->getImage() && !$object->getDeleteImage()) {
                if ($existInCurrentStore) {
                    if ($storeId == $object->getStoreId()) {
                        $where = [
                            'option_type_id = ?' => (int)$optionTypeId,
                            'store_id = ?' => $storeId,
                        ];
                        $bind = ['image' => $object->getImage()];
                        $this->getConnection()->update($imageTable, $bind, $where);
                    }
                } else {
                    $existInDefaultStore = $this->getOptionIdFromOptionTable(
                        $imageTable,
                        (int)$object->getId(),
                        \Magento\Store\Model\Store::DEFAULT_STORE_ID
                    );
                    // we should insert record into not default store only of if it does not exist in default store
                    if (($storeId == \Magento\Store\Model\Store::DEFAULT_STORE_ID && !$existInDefaultStore)
                        || ($storeId != \Magento\Store\Model\Store::DEFAULT_STORE_ID && !$existInCurrentStore)
                    ) {
                        $bind = [
                            'option_type_id' => (int)$object->getId(),
                            'store_id' => $storeId,
                            'image' => $object->getImage(),
                        ];
                        $this->getConnection()->insert($imageTable, $bind);
                    }
                }
            } else {
                if ($storeId
                    && $optionTypeId
                    && $object->getStoreId() > \Magento\Store\Model\Store::DEFAULT_STORE_ID
                ) {
                    $where = [
                        'option_type_id = ?' => (int)$optionTypeId,
                        'store_id = ?' => $storeId,
                    ];
                    $this->getConnection()->delete($imageTable, $where);
                }
            }
        }
    }
}