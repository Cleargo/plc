<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\ProductCollection\Filters\LayeredFilters;

use Manadev\ProductCollection\Enums\Operation;
use Manadev\ProductCollection\Contracts\Filter;

class DecimalFilter extends Filter
{
    /**
     * @var
     */
    protected $attributeId;
    /**
     * @var
     */
    protected $ranges;
    /**
     * @var string
     */
    protected $operation;

    public function __construct($name, $attributeId, $ranges, $operation = Operation::LOGICAL_OR) {
        parent::__construct($name);
        $this->attributeId = $attributeId;
        $this->ranges = $ranges;
        $this->operation = $operation;
    }

    public function getType() {
        return 'layered_decimal';
    }

    public function getAttributeId() {
        return $this->attributeId;
    }

    public function getRanges() {
        return $this->ranges;
    }

    public function getOperation() {
        return $this->operation;
    }
}