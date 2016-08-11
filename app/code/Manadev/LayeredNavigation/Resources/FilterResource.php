<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation\Resources;

use Exception;
use Magento\Framework\Model\ResourceModel\Db;
use Manadev\LayeredNavigation\Models\Filter;

class FilterResource extends Db\AbstractDb {
    protected $editRecordReferenceFields = ['filter_id', 'store_id', 'attribute_id'];

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct() {
        $this->_init('mana_filter', 'id');
    }

    /**
     * @param Filter $model
     * @param array $data
     * @throws Exception
     */
    public function edit($model, array $data) {
        $db = $this->getConnection();

        $db->beginTransaction();

        $edit = $this->loadEdit($model);
        $edit = $this->mergeEdit($edit, $data);
        $this->saveEdit($model, $edit);

        try {
            $db->commit();
        }
        catch (Exception $e) {
            $db->rollBack();

            throw $e;
        }

        $model->afterEdit();
    }

    protected function getEditRecordReferenceFields() {
        return $this->editRecordReferenceFields;
    }

    /**
     * @param Filter $model
     * @return array
     */
    protected function loadEdit($model) {
        $db = $this->getConnection();

        if ($editId = $model->getData('edit_id')) {
            $select = $db->select()
                ->from($this->getTable('mana_filter_edit'))
                ->where("`id` = ?", $editId);

            $edit = $db->fetchRow($select);

            if (isset($edit['id'])) {
                unset($edit['id']);
            }

            foreach ($this->getEditRecordReferenceFields() as $field) {
                if (isset($edit[$field])) {
                    unset($edit[$field]);
                }
            }

            foreach (array_keys($edit) as $field) {
                if (is_null($edit[$field])) {
                    unset($edit[$field]);
                }
            }
        }
        else {
            $edit = [];
        }

        return $edit;
    }

    /**
     * @param array $edit
     * @param array $data
     * @return array
     */
    protected function mergeEdit($edit, $data) {
        foreach ($data as $field => $value) {
            $edit[$field] = $value;
        }

        return $edit;
    }

    /**
     * @param Filter $model
     * @param array $edit
     */
    protected function saveEdit($model, $edit) {
        /* @var $db \Magento\Framework\DB\Adapter\Pdo\Mysql */
        $db = $this->getConnection();

        $isEmpty = $this->isEditEmpty($edit);
        if ($editId = $model->getData('edit_id')) {
            if (!$isEmpty) {
                $db->update($this->getTable('mana_filter_edit'), $edit, $db->quoteInto("`id` = ", $editId));
            }
            else {
                $db->delete($this->getTable('mana_filter_edit'), $db->quoteInto("`id` = ", $editId));
            }
        }
        else {
            if (!$isEmpty) {
                foreach ($this->getEditRecordReferenceFields() as $field) {
                    $edit[$field] = $model->getData($field);
                }
                $db->insert($this->getTable('mana_filter_edit'), $edit);

                $model
                    ->setData('edit_id', $db->lastInsertId($this->getTable('mana_filter_edit')))
                    ->save();
            }
        }
    }

    protected function isEditEmpty($edit) {
        foreach ($edit as $field => $value) {
            if (!is_null($value)) {
                return false;
            }
        }

        return true;
    }
}