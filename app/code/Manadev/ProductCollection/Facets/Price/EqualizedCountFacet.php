<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\ProductCollection\Facets\Price;

use Magento\Framework\DB\Select;

class EqualizedCountFacet extends BaseFacet
{
    protected $ranges;

    /**
     * @var Select
     */
    protected $preparationSelect;

    public function getType() {
         return 'price_equalized_count';
    }

    /**
     * @return mixed
     */
    public function getRanges() {
        return $this->ranges;
    }

    /**
     * @param mixed $ranges
     */
    public function setRanges($ranges) {
        $this->ranges = $ranges;
    }

    /**
     * @return Select
     */
    public function getPreparationSelect() {
        return $this->preparationSelect;
    }

    /**
     * @param Select $preparationSelect
     */
    public function setPreparationSelect($preparationSelect) {
        $this->preparationSelect = $preparationSelect;
    }


}