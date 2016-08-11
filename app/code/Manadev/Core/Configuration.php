<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\Core;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Configuration {
    const RENDER_PRODUCT_LIST_SELECT_IN_HIDDEN_DIV = 'mana_core/debug/product_list_select';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function isProductListSelectRenderedInHiddenDiv() {
        return $this->scopeConfig->isSetFlag(static::RENDER_PRODUCT_LIST_SELECT_IN_HIDDEN_DIV);
    }
}