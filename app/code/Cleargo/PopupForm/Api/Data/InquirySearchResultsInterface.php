<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\PopupForm\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for cms block search results.
 * @api
 */
interface InquirySearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get blocks list.
     *
     * @return \Cleargo\PopupForm\Api\Data\InquiryInterface[]
     */
    public function getItems();

    /**
     * Set blocks list.
     *
     * @param \Cleargo\PopupForm\Api\Data\InquiryInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
