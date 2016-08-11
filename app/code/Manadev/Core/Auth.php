<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
namespace Manadev\Core;

use Magento\Store\Model\Store;
use Manadev\Core\Model\ExtensionFactory;
use Symfony\Component\Config\Definition\Exception\Exception;

class Auth
{
    protected $_loadedModules = [];
    protected $_loadedFeatures = [];
    /**
     * @var Model\Extension\Config
     */
    private $extensionConfig;
    /**
     * @var ExtensionFactory
     */
    private $extensionFactory;
    /**
     * @var Resources\ExtensionCollectionFactory
     */
    private $extensionCollectionFactory;


    public function __construct(
        \Manadev\Core\Model\Feature\Config $extensionConfig,
        ExtensionFactory $extensionFactory,
        \Manadev\Core\Resources\ExtensionCollectionFactory $extensionCollectionFactory,
        \Manadev\Core\Model\Feature\Config $config
    ) {
        $this->extensionConfig = $extensionConfig;
        $this->extensionFactory = $extensionFactory;
        $this->extensionCollectionFactory = $extensionCollectionFactory;
    }

    /**
     * @param $module
     * @param int $store
     * @return bool
     */
    public function isModuleEnabled($module, $store = 0){
        // If not a MANAdev module, assume enabled.
        if(!$this->isInstalledManaModule($module)) {
            return true;
        }

        return $this->extensionConfig->getExtensionOrFeatureModelByModule($module, $store)->isEnabled();
    }

    public function isInstalledManaModule($module) {
        $modules = $this->_initModules();
        return in_array($module, $modules);
    }

    protected function _initModules() {
        if(!$this->_loadedModules) {
            $modules = [];
            foreach($this->extensionConfig->getExtensions() as $extension) {
                $modules[$extension['id']] = $extension['module'];
                foreach($extension['features'] as $feature) {
                    $modules[$feature['id']] = $feature['module'];
                }
            }
            foreach($this->extensionConfig->getRemovedExtensionsConfig() as $removeExtension) {
                $modules[] = $removeExtension['removedModule'];
            }
            $this->_loadedModules = $modules;
        }
        return $this->_loadedModules;
    }
}