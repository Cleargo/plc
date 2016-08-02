<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\ProductPdf\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for cms block search results.
 * @api
 */
interface PdfSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get blocks list.
     *
     * @return \Cleargo\ProductPdf\Api\Data\PdfInterface[]
     */
    public function getItems();

    /**
     * Set blocks list.
     *
     * @param \Cleargo\ProductPdf\Api\Data\PdfInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
