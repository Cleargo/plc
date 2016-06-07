<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\Showroom\Model\ResourceModel;

/**
 * Abstract collection of CMS pages and blocks
 */
abstract class AbstractCollection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null $resource
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
        $this->storeManager = $storeManager;
    }

    /**
     * Perform operations after collection load
     *
     * @param string $tableName
     * @param string $columnName
     * @return void
     */


    /**
     * Add field filter to collection
     *
     * @param array|string $field
     * @param string|int|array|null $condition
     * @return $this
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if ($field === 'store_id') { 
            return $this->addStoreFilter($condition, false);
        }

        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * Add filter by store
     *
     * @param int|array|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return $this
     */
    abstract public function addStoreFilter($store, $withAdmin = true);

    /**
     * Perform adding filter by store
     *
     * @param int|array|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return void
     */
    protected function performAddStoreFilter($store, $withAdmin = true)
    {
        if ($store instanceof \Magento\Store\Model\Store) {
            $store = [$store->getId()];
        }

        if (!is_array($store)) {
            $store = [$store];
        }

        if ($withAdmin) {
            $store[] = \Magento\Store\Model\Store::DEFAULT_STORE_ID;
        }

        $this->addFilter('store', ['in' => $store], 'public');
    }

    /**
     * Join store relation table if there is store filter
     *
     * @param string $tableName
     * @param string $columnName
     * @return void
     */
    protected function joinStoreRelationTable($tableName, $columnName)
    {
        if ($this->getFilter('store')) {
            $this->getSelect()->join(
                ['store_table' => $this->getTable($tableName)],
                'main_table.' . $columnName . ' = store_table.' . $columnName,
                []
            )->group(
                'main_table.' . $columnName
            );
        }
        parent::_renderFiltersBefore();
    }

    protected function joinTypeRelationTable($tableName, $columnName)
    {
        if ($this->getFilter('type')) {
            $this->getSelect()->join(
                ['type_table' => $this->getTable($tableName)],
                'main_table.' . $columnName . ' = type_table.' . $columnName,
                []
            )->group(
                'main_table.' . $columnName
            );
        }
        parent::_renderFiltersBefore();
    }


    protected function performAfterLoad($tableName, $columnName)
    {
        $items = $this->getColumnValues($columnName);
        if (count($items)) {
            $connection = $this->getConnection();
            $select = $connection->select()->from(['dealer_entity_store' => $this->getTable($tableName)])
                ->where('dealer_entity_store.' . $columnName . ' IN (?)', $items);
            $result = $connection->fetchAll($select);
            if ($result) {
                foreach ($this as $item) {
                    $entityId = $item->getData($columnName);
                    $checkIsset = false;
                    $storeIds = array();
                    foreach($result as $sub_result) {
                        if($sub_result[$columnName] == $entityId) {
                            $checkIsset = true;

                            if($sub_result[$columnName] == 0) {
                                $stores = $this->storeManager->getStores(false, true);
                                $storeId = current($stores)->getId();
                                $storeCode = key($stores);
                            } else {
                                $storeId = $sub_result[array_keys($sub_result)[1]];
                                $storeCode = $this->storeManager->getStore($storeId)->getCode();
                                $storeIds[] = $sub_result[array_keys($sub_result)[1]];
                            }
                        }
                    }
                    if(!$checkIsset) {
                        continue;
                    }
                    $item->setData('_first_store_id', $storeId);
                    $item->setData('store_code', $storeCode);
                    $item->setData('store_id', $storeIds);
                }
            }
        }
    }

    protected function performAfterLoadForType($tableName, $columnName)
    {
        $idArr = [];
        $test =0 ;
        $items = $this->getColumnValues($columnName);
        if (count($items)) {
            $connection = $this->getConnection();
            $select = $connection->select()->from(['dealer_entity_store' => $this->getTable($tableName)])
                ->where('dealer_entity_store.' . $columnName . ' IN (?)', $items);
            $result = $connection->fetchAll($select);
            if ($result) {
                foreach ($this as $item) {
                    $entityId = $item->getData($columnName);
                    if (!isset($result[$entityId])) {
                        continue;
                    }

                    $storeId = $result[$item->getData($columnName)];


                    $item->setData('_first_type_id', $storeId["type_id"]);
                    foreach ($result as $row){
                        if($entityId ==$row["location_id"] ){
                            $idArr[] = $row["type_id"];
                        }
                    }
                    $item->setData('type_id', $idArr);
                    $idArr =[];
                }
            }
        }
    }
    /**
     * Get SQL for get record count
     *
     * Extra GROUP BY strip added.
     *
     * @return \Magento\Framework\DB\Select
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(\Magento\Framework\DB\Select::GROUP);

        return $countSelect;
    }
}
