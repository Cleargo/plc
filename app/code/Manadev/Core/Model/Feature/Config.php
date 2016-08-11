<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
namespace Manadev\Core\Model\Feature;

use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Manadev\Core\Model\Extension;
use Manadev\Core\Model\Feature\Config\ModuleConfig;
use Symfony\Component\Config\Definition\Exception\Exception;

class Config
{
    const CACHE_ID = 'manadev_test_config';

    const CACHE_MENU_OBJECT = 'manadev_test_config';

    /**
     * @var \Magento\Framework\App\Cache\Type\Config
     */
    protected $_configCacheType;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @var \Magento\Backend\Model\Menu\Config\Reader
     */
    protected $_configReader;
    /**
     * @var \Manadev\Core\Model\ExtensionFactory
     */
    protected $extensionFactory;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    protected $_extensions = [];
    protected $_removeExtensions = [];
    protected $_features = [];
    protected $_modules = [];
    protected $_extensionModels = [];
    protected $_loaded;
    protected $_store;
    protected $_preparedExtensionModels = [];
    /**
     * @var ModuleConfig
     */
    private $moduleConfig;
    /**
     * @var Extension
     */
    private $extensionModel;

    /**
     * @param \Magento\Backend\Model\Menu\Config\Reader|\Manadev\Core\Model\Extension\Config\Reader $configReader
     * @param \Magento\Framework\App\Cache\Type\Config $configCacheType
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Manadev\Core\Model\ExtensionFactory $extensionFactory
     * @internal param \Magento\Backend\Model\Menu\Builder $menuBuilder
     * @internal param \Magento\Backend\Model\Menu\AbstractDirector $menuDirector
     * @internal param \Magento\Backend\Model\MenuFactory $menuFactory
     * @internal param \Magento\Framework\Event\ManagerInterface $eventManager
     * @internal param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @internal param \Magento\Framework\App\State $appState
     */
    public function __construct(
        \Manadev\Core\Model\Feature\Config\Reader $configReader,
        \Magento\Framework\App\Cache\Type\Config $configCacheType,
        \Psr\Log\LoggerInterface $logger,
        \Manadev\Core\Model\ExtensionFactory $extensionFactory,
        StoreManagerInterface $storeManager,
        ModuleConfig $moduleConfig,
        Extension $extensionModel
    ) {
        $this->_configCacheType = $configCacheType;
        $this->_logger = $logger;
        $this->_configReader = $configReader;
        $this->extensionFactory = $extensionFactory;
        $this->storeManager = $storeManager;
        $this->moduleConfig = $moduleConfig;
        $this->extensionModel = $extensionModel;
    }

    /**
     * @return Extension[]
     */
    public function getExtensions($store = 0) {
        $this->_init();

        if(!isset($this->_preparedExtensionModels[$store])) {
            $result = [];
            foreach($this->_extensionModels as $module => $models) {
                if(isset($models[$store])) {
                    $result[$module] = clone $models[$store];
                    continue;
                }
                $result[$module] = clone $models[0];
            }

            foreach($result as $module => $model) {
                $features = [];
                foreach($model['features'] as $featureModule => $models ) {
                    if(isset($models[$store])) {
                        $features[$featureModule] = clone $models[$store];
                        continue;
                    }
                    $features[$featureModule] = clone $models[0];
                }
                $model->setData('features', $features);
            }
            $this->_preparedExtensionModels[$store] = $result;
        }

        return $this->_preparedExtensionModels[$store];
    }

    /**
     * @param $id
     * @return Extension|null
     */
    public function getExtensionModelById($id, $store = 0){
        $this->_init();
        $this->extensionModel->load($id);
        if($this->extensionModel->getId()) {
            foreach ($this->getExtensions($this->extensionModel->getData('store_id')) as $extensionModel) {
                if($extensionModel->getData('title') == $this->extensionModel->getData('title')) {
                    return $extensionModel;
                }
            }
        }
        return null;
    }

    public function getFeatureModelById($id, $store = 0) {
        $this->_init();
        $this->extensionModel->load($id);
        if($this->extensionModel->getId()) {
            foreach($this->getExtensions($this->extensionModel->getData('store_id')) as $extensionModel) {
                foreach($extensionModel->getData('features') as $featureModel) {
                    if ($featureModel->getData('title') == $this->extensionModel->getData('title')) {
                        return $featureModel;
                    }
                }
            }
        }
        return null;
    }

    public function getExtensionOrFeatureModelById($id, $store = 0) {
        if($extension = $this->getExtensionModelById($id, $store)) {
            return $extension;
        }
        return $this->getFeatureModelById($id, $store);
    }

    /**
     * @param $module
     * @return Extension|null
     */
    public function getExtensionOrFeatureModelByModule($module, $store = 0) {
        foreach($this->_removeExtensions as $removeExtension) {
            if($module == $removeExtension['removedModule']) {
                return $this->getExtensionOrFeatureModelByModule($removeExtension['module'], $store);
            }
        }
        if(isset($this->getExtensions($store)[$module])) {
            return $this->getExtensions($store)[$module];
        }

        foreach($this->getExtensions($store) as $extensionModel) {
            if(isset($extensionModel['features'][$module])) {
                return $extensionModel['features'][$module];
            }
        }
        return null;
    }

    public function getExtensionsOfFeature(Extension $feature, $store = 0) {
        $this->_init();
        $extensions = [];
        foreach ($this->getExtensions($store) as $extensionModel) {
            if(isset($extensionModel->getData('features')[$feature->getData('module')])) {
                $extensions[$extensionModel->getData('module')] = $extensionModel;
            }
        }
        return $extensions;
    }

    protected function _init() {
        if($this->_loaded) {
            return;
        }
        $this->_loaded = true;
        $this->_initConfigData();
        foreach($this->_extensions as $module => $data) {
            $extension = $this->_features[$module];
            $extension['title'] = $data['title'];

            $isExtensionRemoved = false;
            foreach($this->_removeExtensions as &$removeExtension) {
                if($extension['title'] == $removeExtension['extension']) {
                    $isExtensionRemoved = true;
                    $removeExtension['removedModule'] = $extension['module'];
                    break;
                }
            }
            if($isExtensionRemoved) {
                continue;
            }

            $extensionVersion = $extension['version'];
            foreach($this->_modules[$extension['module']]['sequence'] as $moduleSequence) {
                if(!isset($this->_features[$moduleSequence])) continue;
                if (version_compare($extensionVersion, $this->_features[$moduleSequence]['version']) < 0) {
                    $extensionVersion = $this->_features[$moduleSequence]['version'];
                }
            }
            foreach($this->_modules as $moduleSequence => $moduleSequenceData) {
                foreach ($moduleSequenceData['sequence'] as $moduleDependency) {
                    if ($moduleDependency != $module || !isset($this->_features[$moduleSequence])) continue;
                    if (version_compare($extensionVersion, $this->_features[$moduleSequence]['version']) < 0) {
                        $extensionVersion = $this->_features[$moduleSequence]['version'];
                    }
                }
            }
            $extension['version'] = $extensionVersion;

            $extension['features'] = [];
            foreach($this->_modules as $moduleSequence => $moduleSequenceData) {
                if(!isset($this->_features[$moduleSequence]['title'])) {
                    continue;
                }

                foreach($moduleSequenceData['sequence'] as $moduleDependency) {
                    if($moduleDependency != $module) {
                        continue;
                    }
                    if(isset($this->_features[$moduleSequence]) && isset($this->_extensions[$moduleSequence])) {
                        throw new Exception("Module `$moduleSequence` cannot be a feature of `$module` because `$moduleSequence` is an extension");
                    }

                    if(isset($this->_features[$moduleSequence]['title'])) {
                        $extension['features'][$moduleSequence] = $this->_createExtensionModel($this->_features[$moduleSequence]);
                    }
                }
            }
            //Unknown Error, temp fix by CLEARgo Jason
            //$this->_extensionModels[$module] = $this->_createExtensionModel($extension);
        }
    }

    protected function _createExtensionModel($data) {
        $result = [];
        foreach($this->storeManager->getStores(true) as $store) {
            $store_id = $store->getId();
            $extension = $this->extensionFactory->create();
            $extension->setData('store_id', $store_id);
            $extension->load($data['title'], 'title');

            if($store_id == 0) {
                $this->_addConfigData($extension, $data)->save();
                $extension->load($extension->getId());
            }

            if ($extension->getId()) {
                $result[$store_id] = $this->_addConfigData($extension, $data);
            }
        }
        return $result;
    }

    protected function _addConfigData(Extension $extension, $data) {
        $configFields = [
            'version',
            'module',
            'title',
            'features',
        ];
        foreach($configFields as $field) {
            if(array_key_exists($field, $data)) {
                $extension->setData($field, $data[$field]);
            }
        }

        return $extension;
    }

    protected function _initConfigData() {
        foreach ($this->_configReader->read() as $data) {
            $type = $data['type'];
            unset($data['type']);
            switch ($type) {
                case "feature":
                    $this->_features[$data['module']] = $data;
                    break;
                case "extension":
                    $this->_extensions[$data['module']] = $data;
                    break;
                case "removeExtension":
                    $this->_removeExtensions[] = $data;
                    break;
            }
        }
        $this->_modules = $this->moduleConfig->load();
    }

    /**
     * @param $module
     * @return Extension[]
     */
    public function getExtensionsRemovedByModule($module) {
        $result = [];
        foreach($this->_removeExtensions as $removeExtension) {
            if($removeExtension['module'] == $module) {
                $removedModule = $removeExtension['removedModule'];
                $extension = $this->_features[$removedModule];
                $extension['title'] = $this->_extensions[$removedModule]['title'];
                $result[] = $this->_createExtensionModel($extension)[0];
            }
        }

        return $result;
    }

    public function disableDependentModules() {
        $changes = [];
        foreach($this->_features as $feature) {
            if(isset($feature['disable_if_dependent_features_are_disabled'])) {
                $disableFeature = true;

                foreach($this->_modules as $module) {
                    foreach($module['sequence'] as $dependentModule) {
                        if($dependentModule == $feature['module']) {
                            $extension = $this->getExtensionOrFeatureModelByModule($module['name']);
                            if(!is_null($extension) && $extension->isEnabled()) {
                                $disableFeature = false;
                                break;
                            }
                        }
                    }
                }

                $feature['title'] = isset($feature['title']) ? $feature['title'] : $feature['module'];
                /** @var Extension $tmpExtension */
                $tmpExtension = $this->_createExtensionModel($feature)[0];
                $tmpExtension->setData('is_enabled', !$disableFeature)->save();
                $tmpExtensionChanged = $tmpExtension->updateModuleXml();
                $tmpExtension->delete();
                if (!is_null($tmpExtensionChanged) && $tmpExtensionChanged) {
                    $changes[] = $tmpExtension;
                }
            }
        }
        return $changes;
    }

    public function setStore($store_id) {
        if($store_id instanceof Store) {
            $store_id = $store_id->getId();
        }
        $this->_store = $store_id;

        return $this;
    }
    protected function _getStore() {
        if(!isset($this->_store)) {
            return $this->storeManager->getStore()->getId();
        }
        return $this->_store;
    }

    public function getRemovedExtensionsConfig() {
        return $this->_removeExtensions;
    }

}
