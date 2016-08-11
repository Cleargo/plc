<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation\FilterTypes;

use Manadev\LayeredNavigation\Contracts\FilterType;
use Manadev\LayeredNavigation\Registries\FilterTemplates\CategoryFilterTemplates;

class CategoryFilter implements FilterType {
    /**
     * @var CategoryFilterTemplates
     */
    protected $templates;

    public function __construct(CategoryFilterTemplates $templates) {
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