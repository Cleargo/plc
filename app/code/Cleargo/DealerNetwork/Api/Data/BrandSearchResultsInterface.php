<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for dealer brand search results.
 * @api
 */
interface BrandSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get countries list.
     *
     * @return \Cleargo\DealerNetwork\Api\Data\BrandInterface[]
     */
    public function getItems();

    /**
     * Set countries list.
     *
     * @param \Cleargo\DealerNetwork\Api\Data\BrandInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
