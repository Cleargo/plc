<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */


namespace Manadev\ProductCollection\Contracts;


use Manadev\ProductCollection\Query;

interface ProductCollection
{
    /**
     * @return Query
     */
    public function getQuery();

}