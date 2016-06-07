<?php
namespace Cleargo\Contactus\Model\ResourceModel\Grid;

use \Cleargo\Contactus\Model\ResourceModel\AbstractCollection;

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
        $this->_init('Cleargo\Contactus\Model\Grid', 'Cleargo\Contactus\Model\ResourceModel\Grid');
        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    protected function _afterLoad()
    {

        $this->performAfterLoad('contactus_map_location_store', 'location_id');


        return parent::_afterLoad();
    }

    public function toOptionArray()
    {
        return $this->_toOptionArray('location_id', 'title');
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
        $this->joinStoreRelationTable('contactus_map_location_store', 'location_id');
    }
}