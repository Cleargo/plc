<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\PopupForm\Model\ResourceModel\Inquiry;

use \Cleargo\PopupForm\Model\ResourceModel\AbstractCollection;

/**
 * CMS Inquiry Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'inquiry_id';

    /**
     * Perform operations after collection load
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        $this->performAfterLoadForType('customer_inquiry_type', 'inquiry_id');

        return parent::_afterLoad();
    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Cleargo\PopupForm\Model\Inquiry', 'Cleargo\PopupForm\Model\ResourceModel\Inquiry');
    }

    /**
     * Returns pairs inquiry_id - pdf_path
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('inquiry_id', 'name');
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
        return $this;
    }

}
