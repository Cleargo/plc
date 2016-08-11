<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
namespace Manadev\Core\Plugins;

use Closure;
use Magento\Framework\App\Route\Config\Reader;
use Magento\Store\Model\StoreManagerInterface;

class RouteConfigReader
{
    /**
     * @var \Manadev\Core\Auth
     */
    private $auth;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param \Manadev\Core\Auth $auth
     */
    public function __construct(
        \Manadev\Core\Auth $auth,
        StoreManagerInterface $storeManager
    ) {
        $this->auth = $auth;
        $this->storeManager = $storeManager;
    }

    public function aroundRead(Reader $subject, Closure $proceed, $scope = null) {
        $output = $proceed($scope);

        foreach ($output as &$router) {
            foreach($router['routes'] as &$route) {
                foreach($route['modules'] as &$module) {
                    if(
                        $this->auth->isInstalledManaModule($module) &&
                        $this->auth->isModuleEnabled($module, $this->storeManager->getStore()->getId())
                    ) {
                        unset($route);
                    }
                }
            }
        }

        return $output;
    }
}