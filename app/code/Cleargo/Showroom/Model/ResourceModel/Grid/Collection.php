<?php
namespace Cleargo\Showroom\Model\ResourceModel\Grid;

use \Cleargo\Showroom\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{

    protected $_idFieldName = 'location_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Cleargo\Showroom\Model\Grid', 'Cleargo\Showroom\Model\ResourceModel\Grid');
        $this->_map['fields']['store'] = 'store_table.store_id';
        $this->_map['fields']['type'] = 'type_table.type_id';
    }

    protected function _afterLoad()
    {
        $this->setOrder('sort_order','ASC');
        $this->performAfterLoad('showroom_location_store', 'location_id');
        $this->performAfterLoadForType('showroom_location_type', 'location_id');


        return parent::_afterLoad();
    }

    public function toOptionArray()
    {
        return $this->_toOptionArray('location_id', 'address');
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
        $this->joinStoreRelationTable('showroom_location_store', 'location_id');
        //$this->joinTypeRelationTable('showroom_location_type', 'location_id');
    }
}