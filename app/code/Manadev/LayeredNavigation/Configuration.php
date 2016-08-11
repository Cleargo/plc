<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Configuration {
    const PRICE_RANGE_CALCULATION_METHOD = 'catalog/layered_navigation/price_range_calculation';

    const FILTER_INDEX_QUERY_LOGGING = 'mana_core/log/filter_index_queries';

    const DEFAULT_DROPDOWN_TEMPLATE = 'mana_layered_navigation/display/default_dropdown_template';
    const DEFAULT_SWATCH_TEMPLATE = 'mana_layered_navigation/display/default_swatch_template';
    const DEFAULT_DECIMAL_TEMPLATE = 'mana_layered_navigation/display/default_decimal_template';
    const DEFAULT_SHOW_IN = 'mana_layered_navigation/display/default_show_in';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getDefaultDropdownTemplate() {
        return $this->scopeConfig->getValue(static::DEFAULT_DROPDOWN_TEMPLATE);
    }

    public function getDefaultSwatchTemplate() {
        return $this->scopeConfig->getValue(static::DEFAULT_SWATCH_TEMPLATE);
    }

    public function getDefaultDecimalTemplate() {
        return $this->scopeConfig->getValue(static::DEFAULT_DECIMAL_TEMPLATE);
    }

    public function getDefaultPriceTemplate() {
        return 'text_multiple_select';
    }

    public function getDefaultCategoryTemplate() {
        return 'text_single_select';
    }

    public function getDefaultShowIn() {
        return $this->scopeConfig->getValue(static::DEFAULT_SHOW_IN);
    }

    public function isFilterIndexQueryLoggingEnabled() {
        return $this->scopeConfig->isSetFlag(static::FILTER_INDEX_QUERY_LOGGING);
    }

    public function getPriceRangeCalculationMethod() {
        return $this->scopeConfig->getValue(static::PRICE_RANGE_CALCULATION_METHOD, ScopeInterface::SCOPE_STORE);
    }

}