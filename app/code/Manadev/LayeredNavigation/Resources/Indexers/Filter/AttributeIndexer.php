<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation\Resources\Indexers\Filter;

use Magento\Catalog\Model\Product;
use Magento\Framework\Model\ResourceModel\Db;
use Manadev\LayeredNavigation\Configuration;
use Manadev\LayeredNavigation\Contracts\FilterIndexer;
use Zend_Db_Expr;

abstract class AttributeIndexer extends Db\AbstractDb implements FilterIndexer {
    /**
     * @var IndexerScope
     */
    protected $scope;
    /**
     * @var Configuration
     */
    protected $configuration;

    public function __construct(Db\Context $context,
        Configuration $configuration, IndexerScope $scope,
        $resourcePrefix = null)
    {
        parent::__construct($context, $resourcePrefix);

        $this->scope = $scope;
        $this->configuration = $configuration;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct() {
        $this->_setMainTable('mana_filter');
    }

    /**
     * Returns array of store configuration paths which are used in `index`
     * method of this data source
     * @return string[]
     */
    public function getUsedStoreConfigPaths() {
        return [
            Configuration::DEFAULT_DROPDOWN_TEMPLATE,
            Configuration::DEFAULT_SHOW_IN,
        ];
    }

    protected function getIndexedFields() {
        $db = $this->getConnection();

        return [
            'edit_id' => new Zend_Db_Expr("`fge`.`id`"),
            'store_id' => new Zend_Db_Expr("0"),
            'is_deleted' => new Zend_Db_Expr("0"),
            'attribute_id' => new Zend_Db_Expr("`a`.`attribute_id`"),
            'attribute_code' => new Zend_Db_Expr("`a`.`attribute_code`"),
            'unique_key' => new Zend_Db_Expr("CONCAT('attribute-', `a`.`attribute_id`)"),
            'param_name' => new Zend_Db_Expr("`a`.`attribute_code`"),

            'title' => new Zend_Db_Expr("COALESCE(`fge`.`title`, `a`.`frontend_label`)"),
            'position' => new Zend_Db_Expr("COALESCE(`fge`.`position`, `ca`.`position`)"),
            'show_in' => new Zend_Db_Expr($db->quoteInto("?",
                $this->configuration->getDefaultShowIn())),
            'is_enabled_in_categories' => new Zend_Db_Expr("COALESCE(`fge`.`is_enabled_in_categories`, 1)"),
            'is_enabled_in_search' => new Zend_Db_Expr("COALESCE(`fge`.`is_enabled_in_search`, `ca`.`is_filterable_in_search`)"),
            'minimum_product_count_per_option' => new Zend_Db_Expr("IF(`ca`.`is_filterable` = 1, 1, 0)"),
        ];
    }

    /**
     * Inserts or updates records in `mana_filter` table on global level
     * @param array $changes
     */
    public function index($changes = ['all']) {
        $db = $this->getConnection();

        $fields = $this->getIndexedFields();

        $select = $this->select($fields);

        if ($whereClause = $this->scope->limitAttributeIndexing($changes)) {
            $select->where($whereClause);
        }

        // convert SELECT into UPDATE which acts as INSERT on DUPLICATE unique keys
        $sql = $select->insertFromSelect($this->getMainTable(), array_keys($fields));

        // run the statement
        $db->query($sql);
    }

    protected function select($fields) {
        $db = $this->getConnection();

        return $db->select()
            ->distinct()
            ->from(['a' => $this->getTable('eav_attribute')], null)
            ->join(['ca' => $this->getTable('catalog_eav_attribute')],
                "`ca`.`attribute_id` = `a`.`attribute_id` AND `ca`.`is_filterable` <> 0", null)
            ->join(['et' => $this->getTable('eav_entity_type')],
                $db->quoteInto("`et`.`entity_type_id` = `a`.`entity_type_id`
                    AND `et`.`entity_type_code` = ?", Product::ENTITY), null)
            ->joinLeft(['fge' => $this->getTable('mana_filter_edit')],
                "`fge`.`attribute_id` = `a`.`attribute_id` AND `fge`.`store_id` = 0", null)
            ->columns($fields);
    }
}