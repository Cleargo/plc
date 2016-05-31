<?php
namespace Cleargo\Contactus\Model\ResourceModel\Grid;

use \Cleargo\Contactus\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{

    protected $_idFieldName = 'entity_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Cleargo\Contactus\Model\Grid', 'Cleargo\Contactus\Model\ResourceModel\Grid');
    }
    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            $this->performAddStoreFilter($store, $withAdmin);
        }
        return $this;
    }
    protected function _afterLoad()
    {

        $this->performAfterLoad('contactus_map_location_store', 'location_id');
        $this->_previewFlag = false;

        return parent::_afterLoad();
    }
    public function getColumnValues($colName)
    {
        $this->load();

        $col = [];
        foreach ($this->getItems() as $item) {
            $col[] = $item->getData($colName);
            //var_dump(get_class($item));
        }

        return $col;
    }
    protected function performAfterLoad($tableName, $columnName)
    {
        $items = $this->getColumnValues($columnName);

        if (count($items)) {

            $connection = $this->getConnection();
            $select = $connection->select()->from(['cms_entity_store' => $this->getTable($tableName)])
                ->where('cms_entity_store.' . $columnName . ' IN (?)', $items);



            $result = $connection->fetchPairs($select);
            if ($result) {

                foreach ($this as $item) {
                    $entityId = $item->getData('entity_id');

                    if (!isset($result[$entityId])) {
                        continue;
                    }
                    if ($result[$entityId] == 0) {
                        $stores = $this->storeManager->getStores(false, true);
                        $storeId = current($stores)->getId();
                        $storeCode = key($stores);
                    } else {
                        $storeId = $result[$item->getData($columnName)];
                        $storeCode = $this->storeManager->getStore($storeId)->getCode();
                    }
                    $item->setData('_first_store_id', $storeId);
                    $item->setData('store_code', $storeCode);
                    $item->setData('store_id', [$result[$entityId]]);
                    var_dump($item);

                }
            }
        }
    }
}