<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation\Resources\Indexers\Filter;

use Zend_Db_Expr;

class DropdownIndexer extends AttributeIndexer
{
    protected function getIndexedFields() {
        $db = $this->getConnection();

        return array_merge(parent::getIndexedFields(), [
            'type' => new Zend_Db_Expr("'dropdown'"),
            'template' => new Zend_Db_Expr($db->quoteInto("COALESCE(`fge`.`template`, ?)",
                $this->configuration->getDefaultDropdownTemplate())),
        ]);
    }

    protected function select($fields) {
        return parent::select($fields)->where("`a`.`backend_type` IN('int', 'varchar')");
    }
}