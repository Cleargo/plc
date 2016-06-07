<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for dealer dealer search results.
 * @api
 */
interface DealerSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get dealers list.
     *
     * @return \Cleargo\DealerNetwork\Api\Data\DealerInterface[]
     */
    public function getItems();

    /**
     * Set dealers list.
     *
     * @param \Cleargo\DealerNetwork\Api\Data\DealerInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
