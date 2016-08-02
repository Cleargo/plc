<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\Warranty\Model\ResourceModel\Warranty;

use \Cleargo\Warranty\Model\ResourceModel\AbstractCollection;

/**
 * District Warranty Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'warranty_id';

    /**
     * Perform operations after collection load
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        return parent::_afterLoad();
    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Cleargo\Warranty\Model\Warranty', 'Cleargo\Warranty\Model\ResourceModel\Warranty');
    }

    /**
     * Join store relation table if there is store filter
     *
     * @return void
     */
    protected function _renderFiltersBefore()
    {
        
    }
}
