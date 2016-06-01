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


        return parent::_afterLoad();
    }


}