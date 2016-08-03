<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\PopupForm\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * CMS block CRUD interface.
 * @api
 */
interface InquiryRepositoryInterface
{
    /**
     * Save block.
     *
     * @param \Cleargo\PopupForm\Api\Data\InquiryInterface $block
     * @return \Cleargo\PopupForm\Api\Data\InquiryInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\InquiryInterface $block);

    /**
     * Retrieve block.
     *
     * @param int $blockId
     * @return \Cleargo\PopupForm\Api\Data\InquiryInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($blockId);

    /**
     * Retrieve blocks matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Cleargo\PopupForm\Api\Data\InquirySearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete block.
     *
     * @param \Cleargo\PopupForm\Api\Data\InquiryInterface $block
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(Data\InquiryInterface $block);

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
