<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
namespace Manadev\Core\Preferences;

class IndexerConfigData extends \Magento\Indexer\Model\Config\Data
{
    /**
     * @var \Manadev\Core\Model\Feature\Config\ModuleConfig
     */
    private $moduleConfig;

    public function __construct(
        \Magento\Framework\Indexer\Config\Reader $reader,
        \Magento\Framework\Config\CacheInterface $cache,
        \Magento\Indexer\Model\ResourceModel\Indexer\State\Collection $stateCollection,
        \Manadev\Core\Model\Feature\Config\ModuleConfig $moduleConfig,
        $cacheId = 'indexer_config'
    ) {
        parent::__construct($reader, $cache, $stateCollection, $cacheId);
        $this->moduleConfig = $moduleConfig;
    }

    /**
     * Delete all states that are not in configuration
     *
     * @return void
     */
    protected function deleteNonexistentStates() {
        foreach ($this->stateCollection->getItems() as $state) {
            /** @var \Magento\Indexer\Model\Indexer\State $state */
            if (
                !isset($this->_data[$state->getIndexerId()]) &&
                strpos($state->getIndexerId(), "mana") !== 0
            ) {
                $state->delete();
            }
        }
    }
}