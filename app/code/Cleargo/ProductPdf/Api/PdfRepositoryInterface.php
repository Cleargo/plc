<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\ProductPdf\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * CMS block CRUD interface.
 * @api
 */
interface PdfRepositoryInterface
{
    /**
     * Save block.
     *
     * @param \Cleargo\ProductPdf\Api\Data\PdfInterface $block
     * @return \Cleargo\ProductPdf\Api\Data\PdfInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\PdfInterface $block);

    /**
     * Retrieve block.
     *
     * @param int $blockId
     * @return \Cleargo\ProductPdf\Api\Data\PdfInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($blockId);

    /**
     * Retrieve blocks matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Cleargo\ProductPdf\Api\Data\PdfSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete block.
     *
     * @param \Cleargo\ProductPdf\Api\Data\PdfInterface $block
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(Data\PdfInterface $block);

    /**
     * Delete block by ID.
     *
     * @param int $blockId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($blockId);
}
