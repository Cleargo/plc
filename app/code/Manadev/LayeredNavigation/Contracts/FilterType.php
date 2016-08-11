<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation\Contracts;

interface FilterType {
    /**
     * Returns registry of filter templates available for ths filter type
     * @return FilterTemplates
     */
    public function getTemplates();
}