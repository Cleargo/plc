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
        $this->setOrder('sort_order','ASC');
        $this->performAfterLoad('showroom_location_store', 'location_id');
        $this->performAfterLoadForType('showroom_location_type', 'location_id');


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

}