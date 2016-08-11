<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation\Resources\Indexers;

use Exception;
use Magento\Framework\Model\ResourceModel\Db;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Manadev\Core\Exceptions\NotImplemented;
use Manadev\Core\QueryLogger;
use Manadev\LayeredNavigation\Registries\FilterIndexers\PrimaryFilterIndexers;
use Manadev\LayeredNavigation\Registries\FilterIndexers\SecondaryFilterIndexers;
use Psr\Log\LoggerInterface as Logger;
use Manadev\LayeredNavigation\Configuration;
use Manadev\LayeredNavigation\Resources\Indexers\Filter\IndexerScope;
use Zend_Db_Expr;

class FilterIndexer extends Db\AbstractDb {
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var PrimaryFilterIndexers
     */
    protected $primaryFilterIndexers;
    /**
     * @var IndexerScope
     */
    protected $scope;
    /**
     * @var Configuration
     */
    protected $configuration;
    /**
     * @var QueryLogger
     */
    protected $queryLogger;
    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $cacheTypeList;
    /**
     * @var SecondaryFilterIndexers
     */
    protected $secondaryFilterIndexers;

    public function __construct(Db\Context $context, StoreManagerInterface $storeManager, PrimaryFilterIndexers $primaryFilterIndexers,
        SecondaryFilterIndexers $secondaryFilterIndexers, IndexerScope $scope, Configuration $configuration, QueryLogger $queryLogger,
        Logger $logger, \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        $resourcePrefix = null)
    {
        parent::__construct($context, $resourcePrefix);

        $this->storeManager = $storeManager;
        $this->primaryFilterIndexers = $primaryFilterIndexers;
        $this->scope = $scope;
        $this->configuration = $configuration;
        $this->queryLogger = $queryLogger;
        $this->logger = $logger;
        $this->cacheTypeList = $cacheTypeList;
        $this->secondaryFilterIndexers = $secondaryFilterIndexers;
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
     * Indexes all filter settings on global and store level, depending on
     * `$storeId` parameter.
     *
     * @param int $storeId If 0, global and store level settings are indexed,
     *                     otherwise only settings on specified store level
     *                     are indexed.
     * @param bool $useTransaction
     * @throws Exception
     */
    public function reindexAll($storeId = 0, $useTransaction = true) {
        $this->index(['all', 'store' => $storeId], $useTransaction);
    }

    /**
     * Called when attribute is changed. Indexes filters settings inherited
     * from specified attribute on global and store level, depending on
     * `$storeId` parameter.
     *
     * @param array|bool $ids
     * @param int $storeId If 0, global and store level settings are indexed,
     *                     otherwise only settings on specified store level
     *                     are indexed.
     * @param bool $useTransaction
     * @throws Exception
     */
    public function reindexChangedAttributes($ids = false, $storeId = 0,
        $useTransaction = true)
    {
        $this->index(['attributes' => $ids, 'store' => $storeId],
            $useTransaction);
    }

    /**
     * Called when filter is changed. Indexes settings of specified filter on
     * global and store level, depending on `$storeId` parameter.
     *
     * @param int[] $ids
     * @param int $storeId If 0, global and store level settings are indexed,
     *                     otherwise only settings on specified store level
     *                     are indexed.
     * @param bool $useTransaction
     * @throws Exception
     */
    public function reindexChangedFilters($ids, $storeId = 0,
        $useTransaction = true)
    {
        $this->index(['filters' => $ids, 'store' => $storeId], $useTransaction);
    }

    /**
     * Called when store configuration changes. Checks if changed store
     * configuration affect this indexer and if so indexes dependent data
     * sources and all store level settings
     *
     * @param bool|string[] $paths
     * @param int $storeId If 0, global and store level settings are indexed,
     *                     otherwise only settings on specified store level
     *                     are indexed.
     * @param bool $useTransaction
     * @throws Exception
     */
    public function reindexChangedStoreConfiguration($paths = false,
        $storeId = 0, $useTransaction = true)
    {
        throw new NotImplemented();
        /*if (!$paths) {
            // get keys from table which tracks changes in store configuration
        }

        $allIndexers = $this->indexerManager->getList();
        $indexers = [];
        foreach($allIndexers as $indexerName => $dataSource) {
            if (count(array_intersect($paths,
                $dataSource->getUsedStoreConfigPaths()))) {
                $indexers[] = $indexerName;
            }
        }

        if (count($indexers)) {
            $this->index(['dataSources' => $indexers, 'store' => $storeId], $useTransaction);
        }*/
    }

    protected function index($changes = ['all'], $useTransaction = true) {
        if ($this->configuration->isFilterIndexQueryLoggingEnabled()) {
            $this->queryLogger->begin('filter-index');
        }
        // Clear config cache if config is not set
        if(is_null($this->configuration->getDefaultShowIn())) {
            $this->cacheTypeList->cleanType('config');
            throw new Exception('Manadev_LayeredNavigation config is not yet set. Please try again.');
        }

        $db = $this->getConnection();

        if ($useTransaction) {
            $db->beginTransaction();
        }

        try {
            if (empty($changes['store'])) {
                $this->markGlobalRowsAsDeleted($changes);

                foreach($this->primaryFilterIndexers->getList() as $indexer) {
                    $indexer->index($changes);
                }

                foreach($this->secondaryFilterIndexers->getList() as $indexer) {
                    $indexer->index($changes);
                }

                $this->deleteRowsMarkedForDeletion($changes);

                $this->assignGlobalIds($changes);

                foreach($this->storeManager->getStores() as $store) {
                    $this->indexForStore($store, $changes);
                }
            }
            else {
                $this->indexForStore($this->storeManager->getStore($changes['store']), $changes);
            }

            if ($useTransaction) {
                $db->commit();
            }
            if ($this->configuration->isFilterIndexQueryLoggingEnabled()) {
                $this->queryLogger->end('filter-index');
            }
        }
        catch (Exception $e) {
            $this->logger->critical($e);
            if ($useTransaction) {
                $db->rollBack();
            }
            if ($this->configuration->isFilterIndexQueryLoggingEnabled()) {
                $this->queryLogger->end('filter-index');
            }

            throw $e;
        }
    }

    protected function markGlobalRowsAsDeleted($changes) {
        $db = $this->getConnection();

        $db->update($this->getMainTable(), ['is_deleted' => 1],
            $this->scope->limitMarkingForDeletion($changes));
    }

    protected function deleteRowsMarkedForDeletion($changes) {
        $db = $this->getConnection();

        $db->delete($this->getMainTable(), $this->scope->limitDeletion($changes));
    }

    protected function assignGlobalIds($changes) {
        $db = $this->getConnection();

        $db->update($this->getMainTable(), ['filter_id' => new Zend_Db_Expr("`id`")],
            $this->scope->limitIdAssignment($changes));
    }

    /**
     * @param Store $store
     * @param array $changes
     */
    protected function indexForStore($store, $changes = ['all']) {
        $db = $this->getConnection();

        $fields = [
            'edit_id' => new Zend_Db_Expr("`fse`.`id`"),
            'filter_id' => new Zend_Db_Expr("`fg`.`id`"),
            'store_id' => new Zend_Db_Expr($store->getId()),
            'is_deleted' => new Zend_Db_Expr("0"),
            'attribute_id' => new Zend_Db_Expr("`fg`.`attribute_id`"),
            'attribute_code' => new Zend_Db_Expr("`fg`.`attribute_code`"),
            'swatch_input_type' => new Zend_Db_Expr("`fg`.`swatch_input_type`"),
            'unique_key' => new Zend_Db_Expr("CONCAT(`fg`.`unique_key`, '-{$store->getId()}')"),
            'param_name' => new Zend_Db_Expr("`fg`.`param_name`"),
            'type' => new Zend_Db_Expr("`fg`.`type`"),

            'title' => new Zend_Db_Expr("COALESCE(`fse`.`title`, `al`.`value`, `fg`.`title`)"),
            'position' => new Zend_Db_Expr("COALESCE(`fse`.`position`, `fg`.`position`)"),
            'template' => new Zend_Db_Expr("COALESCE(`fse`.`template`, `fg`.`template`)"),
            'show_in' => new Zend_Db_Expr("COALESCE(`fse`.`show_in`, `fg`.`show_in`)"),
            'is_enabled_in_categories' => new Zend_Db_Expr("COALESCE(`fse`.`is_enabled_in_categories`, `fg`.`is_enabled_in_categories`)"),
            'is_enabled_in_search' => new Zend_Db_Expr("COALESCE(`fse`.`is_enabled_in_search`, `fg`.`is_enabled_in_search`)"),
            'minimum_product_count_per_option' => new Zend_Db_Expr("COALESCE(`fse`.`minimum_product_count_per_option`,
                `fg`.`minimum_product_count_per_option`)"),
        ];

        $select = $db->select()
            ->distinct()
            ->from(['fg' => $this->getTable('mana_filter')], null)
            ->joinLeft(['fse' => $this->getTable('mana_filter_edit')],
                $db->quoteInto("`fse`.`filter_id` = `fg`.`id` AND `fse`.`store_id` = ?", $store->getId()), null)
            ->joinLeft(['a' => $this->getTable('eav_attribute')], "`a`.`attribute_id` = `fg`.`attribute_id`", null)
            ->joinLeft(['al' => $this->getTable('eav_attribute_label')],
                $db->quoteInto("`al`.`attribute_id` = `fg`.`attribute_id` AND `al`.`store_id` = ?", $store->getId()), null)
            ->columns($fields);

        if ($whereClause = $this->scope->limitStoreLevelIndexing($changes)) {
            $select->where($whereClause);
        }

        // convert SELECT into UPDATE which acts as INSERT on DUPLICATE unique keys
        $sql = $select->insertFromSelect($this->getMainTable(), array_keys($fields));

        // run the statement
        $db->query($sql);
    }
}