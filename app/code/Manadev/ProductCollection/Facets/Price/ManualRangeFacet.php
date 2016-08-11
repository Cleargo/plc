<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\ProductCollection\Facets\Price;

class ManualRangeFacet extends BaseFacet
{
    public function getType() {
         return 'price_manual_range';
   }
}