<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for district district search results.
 * @api
 */
interface DistrictSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get districts list.
     *
     * @return \Cleargo\FindYourLock\Api\Data\DistrictInterface[]
     */
    public function getItems();

    /**
     * Set districts list.
     *
     * @param \Cleargo\FindYourLock\Api\Data\DistrictInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
