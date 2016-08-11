<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
namespace Manadev\Core\Model;

use Magento\Framework\Model\AbstractModel;

class Extension extends AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Manadev\Core\Resources\ExtensionResource');
    }

    public function isEnabled() {
        return $this->getData('is_enabled') == "1";
    }

    public function updateModuleXml() {
        return $this->getResource()->updateModuleXml($this);
    }

    /**
     * Retrieve model resource
     *
     * @return \Manadev\Core\Resources\ExtensionResource
     */
    public function getResource() {
        return parent::getResource();
    }
}