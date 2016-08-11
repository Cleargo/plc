<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation\FilterTypes;

use Manadev\LayeredNavigation\Contracts\FilterTemplates;
use Manadev\LayeredNavigation\Contracts\FilterType;
use Manadev\LayeredNavigation\Registries\FilterTemplates\PriceFilterTemplates;

class PriceFilter implements FilterType {
    /**
     * @var PriceFilterTemplates
     */
    protected $templates;

    public function __construct(PriceFilterTemplates $templates) {
        $this->templates = $templates;
    }

    /**
     * Returns registry of filter templates available for ths filter type
     * @return FilterTemplates
     */
    public function getTemplates() {
        return $this->templates;
    }
}