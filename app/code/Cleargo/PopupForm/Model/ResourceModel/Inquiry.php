<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\PopupForm\Model\ResourceModel;

/**
 * CMS pdf model
 */
class Inquiry extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Construct
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param string $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->_storeManager = $storeManager;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('customer_inquiry', 'inquiry_id');
    }

    /**
     * Process pdf data before deleting
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return \Cleargo\PopupForm\Model\ResourceModel\Page
     */
    protected function _beforeDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        return parent::_beforeDelete($object);
    }

    /**
     * Perform operations before object save
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {

        /*if (!$this->getIsUniqueInquiryToStores($object)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('A pdf identifier with the same properties already exists in the selected store.')
            );
        }*/

        if(!$object->getData('customer_id')){
            $object->setData('customer_id',null);
        }

        return $this;
    }

    /**
     * Perform operations after object save
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {

        $oldTypes = $this->lookupTypeIds($object->getId());

        if($object->getQuestionType()){
            $newTypes = (array)$object->getQuestionType();
        } else {
            $newTypes = (array) $object->getData('question_type_id');
        }

        if (empty($newTypes)) {
            $newTypes = (array)$object->getQuestionTypeId();
        }


        $table = $this->getTable('customer_inquiry_type');
        $insert = array_diff($newTypes, $oldTypes);
        $delete = array_diff($oldTypes, $newTypes);

        if ($delete) {
            $where = ['inquiry_id = ?' => (int)$object->getId(), 'question_type_id IN (?)' => $delete];
            $this->getConnection()->delete($table, $where);
        }

        if ($insert) {
            $data = [];

            foreach ($insert as $typeId) {
                $data[] = ['inquiry_id' => (int)$object->getId(), 'question_type_id' => (int)$typeId];
            }

            $this->getConnection()->insertMultiple($table, $data);
        }

        //var_dump(13);die();

        return parent::_afterSave($object);
    }

    public function lookupTypeIds($inquiry_id)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from(
            $this->getTable('customer_inquiry_type'),
            'question_type_id'
        )->where(
            'inquiry_id = ?',
            (int)$inquiry_id
        );

        return $connection->fetchCol($select);
    }

    /**
     * Load an object using 'identifier' field if there's no field specified and value is not numeric
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param mixed $value
     * @param string $field
     * @return $this
     */
    public function load(\Magento\Framework\Model\AbstractModel $object, $value, $field = null)
    {

        return parent::load($object, $value, $field);
    }

    /**
     * Perform operations after object load
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->getId()) {
            $types = $this->lookupTypeIds($object->getId());

            $object->setData('question_type_id', $types);
        }

        return parent::_afterLoad($object);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param \Cleargo\PopupForm\Model\Inquiry $object
     * @return \Magento\Framework\DB\Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        return $select;
    }

    /**
     * Check for unique of identifier of pdf to selected store(s).
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsUniqueInquiryToStores(\Magento\Framework\Model\AbstractModel $object)
    {

        return true;
    }
}
