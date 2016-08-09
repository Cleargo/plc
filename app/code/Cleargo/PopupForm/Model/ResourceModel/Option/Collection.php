<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\PopupForm\Model\ResourceModel\Option;

use \Cleargo\PopupForm\Model\ResourceModel\AbstractCollection;

/**
 * CMS Option Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'question_type_id';

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
        $this->_init('Cleargo\PopupForm\Model\Option', 'Cleargo\PopupForm\Model\ResourceModel\Option');
    }

    /**
     * Returns pairs inquiry_id - pdf_path
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('question_type_id', 'default_label');
    }
    /**
     * Returns pairs inquiry_id - pdf_path
     *
     * @return array
     */

    public function toCheckboxArray($storeCode)
    {
        $res = [];
        $additional['label'] = 'default_label';
        $additional['value'] = 'question_type_id';

        foreach ($this as $item) {
            $trans =[];
            if($item->getData('trans_label')){
                $trans =(array) json_decode($item->getData('trans_label')) ;
            }

            foreach ($additional as $code => $field) {
                if( $code != 'label' ||empty($trans) || !isset($trans[$storeCode]) || $trans[$storeCode] ==""){
                    $data[$code] = $item->getData($field);
                } else {
                    $data[$code] = $trans[$storeCode];
                }
            }
            $res[] = $data;
        }
        return $res;
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
