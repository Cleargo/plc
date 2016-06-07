<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Model\ResourceModel\Brand;

use \Cleargo\DealerNetwork\Model\ResourceModel\AbstractCollection;

/**
 * Dealer Brand Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'brand_id';

    /**
     * Perform operations after collection load
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        $this->performAfterLoad('dealer_brand_store', 'brand_id');

        return parent::_afterLoad();
    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Cleargo\DealerNetwork\Model\Brand', 'Cleargo\DealerNetwork\Model\ResourceModel\Brand');
        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    /**
     * Add filter by store
     *
     * @param int|array|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        $this->performAddStoreFilter($store, $withAdmin);

        return $this;
    }

    /**
     * Join store relation table if there is store filter
     *
     * @return void
     */
    protected function _renderFiltersBefore()
    {
        $this->joinStoreRelationTable('dealer_brand_store', 'brand_id');
    }

    /**
     * Perform operations after collection load
     *
     * @param string $tableName
     * @param string $columnName
     * @return void
     */
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

            $connection = $this->getConnection();
            $select = $connection->select()->from(['dealer_entity_label' => $this->getTable('dealer_brand_label')])
                ->where('dealer_entity_label.' . $columnName . ' IN (?)', $items);
            $result = $connection->fetchAll($select);
            if ($result) {
                foreach ($this as $item) {
                    $entityId = $item->getData($columnName);
                    $frontend_labels = array();
                    foreach($result as $sub_result) {
                        $frontend_labels[$sub_result['store_id']] = $sub_result['value'];
                    }
                    $item->setData('frontend_label', $frontend_labels);
                }
            }
        }
    }
}
