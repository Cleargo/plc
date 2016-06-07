<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Model\ResourceModel;

/**
 * Dealer brand model
 */
class Brand extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Construct
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param string $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->_storeManager = $storeManager;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('dealer_brand', 'brand_id');
    }

    /**
     * Process brand data before deleting
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return \Cleargo\DealerNetwork\Model\ResourceModel\Brand
     */
    protected function _beforeDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        $condition = ['brand_id = ?' => (int)$object->getId()];

        $this->getConnection()->delete($this->getTable('dealer_brand_store'), $condition);
        $this->getConnection()->delete($this->getTable('dealer_brand_label'), $condition);

        return parent::_beforeDelete($object);
    }

    /**
     * Perform operations before object save
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if (!$this->getIsUniqueBrand($object)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('A brand identifier already exists')
            );
        }
        return $this;
    }

    /**
     * Perform operations after object save
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();

        $table = $this->getTable('dealer_brand_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);

        if ($delete) {
            $where = ['brand_id = ?' => (int)$object->getId(), 'store_id IN (?)' => $delete];

            $this->getConnection()->delete($table, $where);
        }

        if ($insert) {
            $data = [];

            foreach ($insert as $storeId) {
                $data[] = ['brand_id' => (int)$object->getId(), 'store_id' => (int)$storeId];
            }

            $this->getConnection()->insertMultiple($table, $data);
        }

        $newLabels = (array)$object->getFrontendLabel();
        $table = $this->getTable('dealer_brand_label');

        if ($newLabels) {
            $where = ['brand_id = ?' => (int)$object->getId()];

            $this->getConnection()->delete($table, $where);

            $data = [];

            foreach ($newLabels as $storeId => $value) {
                if($value != "") {
                    $data[] = ['brand_id' => (int)$object->getId(), 'store_id' => (int)$storeId, 'value' => $value];
                }
            }

            if(count($data) > 0) {
                $this->getConnection()->insertMultiple($table, $data);
            }
        }

        return parent::_afterSave($object);
    }

    /**
     * Load an object using 'identifier' field if there's no field specified and value is not numeric
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param mixed $value
     * @param string $field
     * @return $this
     */
    public function load(\Magento\Framework\Model\AbstractModel $object, $value, $field = null)
    {
        if (!is_numeric($value) && $field === null) {
            $field = 'identifier';
        }

        return parent::load($object, $value, $field);
    }

    /**
     * Perform operations after object load
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_id', $stores);
            $object->setData('stores', $stores);

            $labels = $this->lookupLabels($object->getId());
            $frontend_labels = [];
            foreach($labels as $label) {
                $frontend_labels[$label['store_id']] = $label['value'];
            }
            $object->setData('frontend_label', $frontend_labels);
        }

        return parent::_afterLoad($object);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param \Cleargo\DealerNetwork\Model\Brand $object
     * @return \Magento\Framework\DB\Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $stores = [(int)$object->getStoreId(), \Magento\Store\Model\Store::DEFAULT_STORE_ID];

            $select->join(
                ['cbs' => $this->getTable('dealer_brand_store')],
                $this->getMainTable() . '.brand_id = cbs.brand_id',
                ['store_id']
            )->where(
                'is_active = ?',
                1
            )->where(
                'cbs.store_id in (?)',
                $stores
            )->order(
                'store_id DESC'
            )->limit(
                1
            );
        }

        return $select;
    }

    /**
     * Check for unique of identifier of brand to selected store(s).
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsUniqueBrand(\Magento\Framework\Model\AbstractModel $object)
    {
        $select = $this->getConnection()->select()->from(
            ['cb' => $this->getMainTable()]
        )->where(
            'cb.identifier = ?',
            $object->getData('identifier')
        );

        if ($object->getId()) {
            $select->where('cb.brand_id <> ?', $object->getId());
        }

        if ($this->getConnection()->fetchRow($select)) {
            return false;
        }

        return true;
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupStoreIds($id)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from(
            $this->getTable('dealer_brand_store'),
            'store_id'
        )->where(
            'brand_id = :brand_id'
        );

        $binds = [':brand_id' => (int)$id];

        return $connection->fetchCol($select, $binds);
    }

    /**
     * Get frontend labels to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupLabels($id)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from(
            $this->getTable('dealer_brand_label'),
            ['store_id','value']
        )->where(
            'brand_id = :brand_id'
        );

        $binds = [':brand_id' => (int)$id];

        return $connection->fetchAll($select, $binds);
    }
}
