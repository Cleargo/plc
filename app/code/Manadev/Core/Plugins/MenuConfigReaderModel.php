<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
namespace Manadev\Core\Plugins;

use Closure;
use Magento\Backend\Model\Menu\Config\Reader;

class MenuConfigReaderModel {
    /**
     * @var \Manadev\Core\Auth
     */
    private $auth;

    /**
     * @param \Manadev\Core\Auth $auth
     * @param \Manadev\Core\Model\ExtensionConfigReader $extensionConfigReader
     */
    public function __construct(
        \Manadev\Core\Auth $auth
    ) {
        $this->auth = $auth;
    }

    public function aroundRead(Reader $subject, Closure $proceed, $scope = null){
        $output = $proceed($scope);

        foreach($output as $key => $data) {
            if(!isset($data['module'])) continue;
            $module = $data['module'];
            if(!$this->auth->isModuleEnabled($module, 0)){
                unset($output[$key]);
            }
        }

        return $output;
    }
}