<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */namespace Manadev\Core\Plugins;

use Closure;
use Magento\Framework\Indexer\Config\Reader;

class IndexerConfigReaderModel {
    /**
     * @var \Manadev\Core\Auth
     */
    private $auth;

    /**
     * @param \Manadev\Core\Auth $auth
     */
    public function __construct(
        \Manadev\Core\Auth $auth
    ) {
        $this->auth = $auth;
    }

    public function aroundRead(Reader $subject, Closure $proceed, $scope = null){
        $output = $proceed($scope);

        foreach($output as $key => $data) {
            $parts = explode('\\', $data['action_class']);
            $module = implode("_", [$parts[0], $parts[1]]);
            if(!$this->auth->isModuleEnabled($module, 0)){
                unset($output[$key]);
            }
        }

        return $output;
    }
}