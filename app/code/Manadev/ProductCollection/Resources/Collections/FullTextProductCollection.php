<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\ProductCollection\Resources\Collections;

use Magento\Catalog\Model\ResourceModel\Product;
use Manadev\Core\Auth;
use Manadev\Core\QueryLogger;
use Manadev\ProductCollection\Contracts\ProductCollection;
use Manadev\ProductCollection\FacetGenerator;
use Manadev\ProductCollection\Factory;
use Manadev\ProductCollection\FilterGenerator;
use Manadev\ProductCollection\Filters\SearchFilter;
use Manadev\ProductCollection\Query;
use Manadev\ProductCollection\QueryRunner;

class FullTextProductCollection extends \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection implements ProductCollection
{
    /**
     * @var QueryLogger
     */
    protected $queryLogger;

    /**
     * @var Query
     */
    protected $query;
    /**
     * @var QueryRunner
     */
    protected $queryRunner;
    /**
     * @var Factory
     */
    protected $factory;
    /**
     * @var Auth
     */
    private $auth;
    /**
     * @var FacetGenerator
     */
    private $facetGenerator;
    /**
     * @var FilterGenerator
     */
    private $filterGenerator;

    /**
     * @param \Magento\Framework\Data\Collection\EntityFactory $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Eav\Model\Config $eavConfig
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Magento\Eav\Model\EntityFactory $eavEntityFactory
     * @param \Magento\Catalog\Model\ResourceModel\Helper $resourceHelper
     * @param \Magento\Framework\Validator\UniversalFactory $universalFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param \Magento\Catalog\Model\Indexer\Product\Flat\State $catalogProductFlatState
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Catalog\Model\Product\OptionFactory $productOptionFactory
     * @param \Magento\Catalog\Model\ResourceModel\Url $catalogUrl
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param \Magento\Customer\Api\GroupManagementInterface $groupManagement
     * @param \Magento\Search\Model\QueryFactory $catalogSearchData
     * @param \Magento\Framework\Search\Request\Builder $requestBuilder
     * @param \Magento\Search\Model\SearchEngine $searchEngine
     * @param \Magento\Framework\Search\Adapter\Mysql\TemporaryStorageFactory $temporaryStorageFactory
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $connection
     * @param string $searchRequestName
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Eav\Model\EntityFactory $eavEntityFactory,
        \Magento\Catalog\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Validator\UniversalFactory $universalFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Catalog\Model\Indexer\Product\Flat\State $catalogProductFlatState,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\Product\OptionFactory $productOptionFactory,
        \Magento\Catalog\Model\ResourceModel\Url $catalogUrl,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Customer\Api\GroupManagementInterface $groupManagement,
        \Magento\Search\Model\QueryFactory $catalogSearchData,
        \Magento\Framework\Search\Request\Builder $requestBuilder,
        \Magento\Search\Model\SearchEngine $searchEngine,
        \Magento\Framework\Search\Adapter\Mysql\TemporaryStorageFactory $temporaryStorageFactory,
        QueryLogger $queryLogger,
        Factory $factory,
        QueryRunner $queryRunner,
        Auth $auth,
        FacetGenerator $facetGenerator,
        FilterGenerator $filterGenerator,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        $searchRequestName = 'catalog_view_container'
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $eavConfig, $resource, $eavEntityFactory,
            $resourceHelper, $universalFactory, $storeManager, $moduleManager, $catalogProductFlatState, $scopeConfig, $productOptionFactory,
            $catalogUrl, $localeDate, $customerSession, $dateTime, $groupManagement, $catalogSearchData, $requestBuilder, $searchEngine,
            $temporaryStorageFactory, $connection, $searchRequestName
        );
        $this->queryLogger = $queryLogger;

        $this->query = $factory->createQuery();
        $this->query->setProductCollection($this);

        $this->queryRunner = $queryRunner;
        $this->factory = $factory;
        $this->auth = $auth;
        $facetGenerator->setCollection($this);
        $this->facetGenerator = $facetGenerator;
        $this->filterGenerator = $filterGenerator;
    }

    public function load($printQuery = false, $logQuery = false) {
        $this->queryLogger->begin('product-collection');
        parent::load($printQuery, $logQuery);
        $this->queryLogger->end('product-collection');

        return $this;
    }

    protected function _renderFiltersBefore() {
        if($this->auth->isModuleEnabled('Manadev_ProductCollection', $this->_storeManager->getStore()->getId())) {
            $this->queryRunner->run($this);
        } else {
            parent::_renderFiltersBefore();
        }
    }

    /**
     * @return Query
     */
    public function getQuery() {
        return $this->query;
    }

    protected function _initSelect() {
        parent::_initSelect();
        $this->getSelect()->distinct();
        return $this;
    }

    public function getFacetedData($field) {
        $this->_renderFilters();
        return $this->facetGenerator->getFacetedData($field);
    }

    public function addFieldToFilter($field, $condition = null) {
        $filterGroup = $this->query->getFilterGroup('productcollection');
        if(!$filterGroup->getOperand($field)) {
            $filter = $this->filterGenerator->getFilter($field, $condition);
            if($filter) {
                $filterGroup->addOperand($filter);
            }
        }
        return $this;
    }

    public function addCategoryFilter(\Magento\Catalog\Model\Category $category) {
        parent::addCategoryFilter($category);
        $this->query->setCategory($category);
        return $this;
    }

    /**
     * Add search query filter
     *
     * @param string $query
     * @return $this
     */
    public function addSearchFilter($query)
    {
        $self = $this;

        /* @var $searchFilter SearchFilter */
        $searchFilter = $this->query->getFilterGroup('search', function($name) use ($self) {
            return $self->factory->createSearchFilter($name);
        });

        $searchFilter->addSearchText($query);

        return parent::addSearchFilter($query);
    }

}