<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\ProductCollection\Facets\Decimal;

use Manadev\ProductCollection\Facets\Price\BaseFacet;

class EqualizedRangeFacet extends BaseFacet
{
    /**
     * @var
     */
    protected $attributeId;

    public function __construct($name, $attributeId, $appliedRanges) {
        parent::__construct($name, $appliedRanges);
        $this->attributeId = $attributeId;
    }

    public function getType() {
         return 'decimal_equalized_range';
   }

    /**
     * @return mixed
     */
    public function getAttributeId() {
        return $this->attributeId;
    }
}