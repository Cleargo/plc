<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\Warranty\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Dealer warranty CRUD interface.
 * @api
 */
interface WarrantyRepositoryInterface
{
    /**
     * Save warranty.
     *
     * @param \Cleargo\Warranty\Api\Data\WarrantyInterface $warranty
     * @return \Cleargo\Warranty\Api\Data\WarrantyInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Cleargo\Warranty\Api\Data\WarrantyInterface $warranty);

    /**
     * Retrieve warranty.
     *
     * @param int $warrantyId
     * @return \Cleargo\Warranty\Api\Data\WarrantyInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($warrantyId);

    /**
     * Retrieve warrantys matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Cleargo\Warranty\Api\Data\WarrantySearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete warranty.
     *
     * @param \Cleargo\Warranty\Api\Data\WarrantyInterface $warranty
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Cleargo\Warranty\Api\Data\WarrantyInterface $warranty);

    /**
     * Delete warranty by ID.
     *
     * @param int $warrantyId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($warrantyId);
}
