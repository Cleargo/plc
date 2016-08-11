<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation\Resources\Indexers\Filter;

use Zend_Db_Expr;

class DecimalIndexer extends AttributeIndexer
{
    protected function getIndexedFields() {
        $db = $this->getConnection();

        return array_merge(parent::getIndexedFields(), [
            'type' => new Zend_Db_Expr("'decimal'"),
            'template' => new Zend_Db_Expr($db->quoteInto("COALESCE(`fge`.`template`, ?)",
                $this->configuration->getDefaultDecimalTemplate())),
        ]);
    }

    protected function select($fields) {
        return parent::select($fields)->where("`a`.`backend_type` = 'decimal' AND `a`.`attribute_code` <> 'price'");
    }

}