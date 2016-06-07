<?php
namespace Cleargo\Contactus\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Grid post mysql resource
 */
class Grid extends AbstractDb
{
    const GRID_ID = 'location_id'; // We define the id fieldname

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'contactus'; // parent value is 'core_abstract'

    /**
     * Name of the event object
     *
     * @var string
     */
    protected $_eventObject = 'grid'; // parent value is 'object'

    /**
     * Name of object id field
     *
     * @var string
     */
    protected $_idFieldName = self::GRID_ID;
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected $_store = null;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $dateTime;

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->_storeManager = $storeManager;
        $this->dateTime = $dateTime;
    }
    protected function _construct()
    {
        $this->_init('contactus_map_location', 'location_id');
    }

    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {

        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());

            $object->setData('store_id', $stores);
        }

        return parent::_afterLoad($object);
    }

    public function lookupStoreIds($location_id)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from(
            $this->getTable('contactus_map_location_store'),
            'store_id'
        )->where(
            'location_id = ?',
            (int)$location_id
        );

        return $connection->fetchCol($select);
    }

    protected function _beforeDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        $condition = ['location_id = ?' => (int)$object->getId()];

        $this->getConnection()->delete($this->getTable('contactus_map_location_store'), $condition);

        return parent::_beforeDelete($object);
    }

    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
        $table = $this->getTable('contactus_map_location_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);

        if ($delete) {
            $where = ['location_id = ?' => (int)$object->getId(), 'store_id IN (?)' => $delete];

            $this->getConnection()->delete($table, $where);
        }

        if ($insert) {
            $data = [];

            foreach ($insert as $storeId) {
                $data[] = ['location_id' => (int)$object->getId(), 'store_id' => (int)$storeId];
            }

            $this->getConnection()->insertMultiple($table, $data);
        }

        return parent::_afterSave($object);
    }

    public function setStore($store)
    {
        $this->_store = $store;
        return $this;
    }

    /**
     * Retrieve store model
     *
     * @return \Magento\Store\Model\Store
     */
    public function getStore()
    {
        return $this->_storeManager->getStore($this->_store);
    }
}