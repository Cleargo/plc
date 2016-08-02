<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\Warranty\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for warranty warranty search results.
 * @api
 */
interface WarrantySearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get warrantys list.
     *
     * @return \Cleargo\Warranty\Api\Data\WarrantyInterface[]
     */
    public function getItems();

    /**
     * Set warrantys list.
     *
     * @param \Cleargo\Warranty\Api\Data\WarrantyInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
