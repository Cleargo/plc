<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\ProductCollection\Facets\Category;

use Magento\Catalog\Model\Category;
use Manadev\ProductCollection\Contracts\Facet;

class ChildFacet extends Facet
{
    /**
     * @var Category|bool
     */
    protected $appliedCategory;

    public function __construct($name, $appliedCategory) {
        parent::__construct($name);
        $this->appliedCategory = $appliedCategory;
    }

    public function getType() {
        return 'category_child';
    }

    /**
     * @return Category|bool
     */
    public function getAppliedCategory() {
        return $this->appliedCategory;
    }

    /**
     * @param int|bool $appliedCategory
     */
    public function setAppliedCategory($appliedCategory) {
        $this->appliedCategory = $appliedCategory;
    }


}