<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for dealer region search results.
 * @api
 */
interface RegionSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get regions list.
     *
     * @return \Cleargo\DealerNetwork\Api\Data\RegionInterface[]
     */
    public function getItems();

    /**
     * Set regions list.
     *
     * @param \Cleargo\DealerNetwork\Api\Data\RegionInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
