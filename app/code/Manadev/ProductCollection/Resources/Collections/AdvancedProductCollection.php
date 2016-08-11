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
use Manadev\ProductCollection\Factory;
use Manadev\ProductCollection\Filters\SearchFilter;
use Manadev\ProductCollection\Query;
use Manadev\ProductCollection\QueryRunner;

class AdvancedProductCollection extends \Magento\CatalogSearch\Model\ResourceModel\Advanced\Collection implements ProductCollection
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
     * List Of filters
     * @var array
     */
    protected $filters = [];
    /**
     * @var Auth
     */
    private $auth;

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
        \Magento\CatalogSearch\Model\Advanced\Request\Builder $requestBuilder,
        \Magento\Search\Model\SearchEngine $searchEngine,
        \Magento\Framework\Search\Adapter\Mysql\TemporaryStorageFactory $temporaryStorageFactory,
        QueryLogger $queryLogger,
        Factory $factory,
        QueryRunner $queryRunner,
        Auth $auth,
        $connection = null)
    {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $eavConfig, $resource,
            $eavEntityFactory, $resourceHelper, $universalFactory, $storeManager, $moduleManager,
            $catalogProductFlatState, $scopeConfig, $productOptionFactory, $catalogUrl, $localeDate,
            $customerSession, $dateTime, $groupManagement, $requestBuilder, $searchEngine, $temporaryStorageFactory,
            $connection);

        $this->queryLogger = $queryLogger;

        $this->query = $factory->createQuery();
        $this->query->setProductCollection($this);

        $this->queryRunner = $queryRunner;
        $this->factory = $factory;
        $this->auth = $auth;
    }

    public function load($printQuery = false, $logQuery = false) {
        $this->queryLogger->begin('product-collection');
        parent::load($printQuery, $logQuery);
        $this->queryLogger->end('product-collection');

        return $this;
    }

    protected function _renderFiltersBefore() {
        if($this->auth->isModuleEnabled('Manadev_ProductCollection', $this->_storeManager->getStore()->getId())) {
            if ($this->filters) {
                foreach ($this->filters as $attributes) {
                    foreach ($attributes as $attributeCode => $attributeValue) {
                        $attributeCode = $this->getAttributeCode($attributeCode);
                        foreach($attributeValue as $condition => $value) {
                            if($condition == "like") {
                                $attributeValue[$condition] = "%{$value}%";
                            }
                        }
                        $this->addFieldToFilter($attributeCode, $attributeValue);
                    }
                }
            }
            $this->queryRunner->run($this);
        } else {
            parent::_renderFiltersBefore();
        }
    }

    /**
     * @return $this
     */
    protected function _renderFilters() {
        return parent::_renderFilters();
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

        return $this;
    }

    /**
     * Add not indexable fields to search
     *
     * @param array $fields
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addFieldsToFilter($fields)
    {
        if ($fields) {
            $this->filters = array_merge($this->filters, $fields);
        }
        return $this;
    }

    protected function getAttributeCode($attributeCode)
    {
        if (is_numeric($attributeCode)) {
            $attributeCode = $this->_eavConfig->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $attributeCode)
                ->getAttributeCode();
        }

        return $attributeCode;
    }
}