<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for dealer dealer search results.
 * @api
 */
interface LockSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get dealers list.
     *
     * @return \Cleargo\FindYourLock\Api\Data\LockInterface[]
     */
    public function getItems();

    /**
     * Set dealers list.
     *
     * @param \Cleargo\FindYourLock\Api\Data\LockInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
