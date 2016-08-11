<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\ProductCollection\Facets\Price;

class EqualizedRangeFacet extends BaseFacet
{
    public function getType() {
         return 'price_equalized_range';
   }
}