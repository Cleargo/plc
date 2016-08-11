<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
namespace Manadev\Core\Plugins;

use Magento\Store\Model\StoreManagerInterface;
use Manadev\Core\Auth;

class ModuleOutput
{
    /**
     * @var Auth
     */
    private $auth;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(Auth $auth, StoreManagerInterface $storeManager){
        $this->auth = $auth;
        $this->storeManager = $storeManager;
    }

    public function afterGetFiles(\Magento\Framework\View\File\Collector\Decorator\ModuleOutput $subject, $result){
        /**
         * @var int $x
         * @var \Magento\Framework\View\File $view
         */
        foreach($result as $x => $view) {
            if(!$this->auth->isModuleEnabled($view->getModule(), $this->storeManager->getStore()->getId())) {
                unset($result[$x]);
            }
        }

        return $result;
    }
}