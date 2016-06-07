<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for dealer country search results.
 * @api
 */
interface CountrySearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get countries list.
     *
     * @return \Cleargo\DealerNetwork\Api\Data\CountryInterface[]
     */
    public function getItems();

    /**
     * Set countries list.
     *
     * @param \Cleargo\DealerNetwork\Api\Data\CountryInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
