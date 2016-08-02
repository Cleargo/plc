<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for lock region search results.
 * @api
 */
interface RegionSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get regions list.
     *
     * @return \Cleargo\FindYourLock\Api\Data\RegionInterface[]
     */
    public function getItems();

    /**
     * Set regions list.
     *
     * @param \Cleargo\FindYourLock\Api\Data\RegionInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
