<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\ProductPdf\Model\ResourceModel\Pdf;

use \Cleargo\ProductPdf\Model\ResourceModel\AbstractCollection;

/**
 * CMS Pdf Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'pdf_id';

    /**
     * Perform operations after collection load
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        $this->performAfterLoad('product_pdf_store', 'pdf_id');

        return parent::_afterLoad();
    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Cleargo\ProductPdf\Model\Pdf', 'Cleargo\ProductPdf\Model\ResourceModel\Pdf');
        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    /**
     * Returns pairs pdf_id - pdf_path
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('pdf_id', 'pdf_path');
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
        $this->joinStoreRelationTable('product_pdf_store', 'pdf_id');
    }
}
