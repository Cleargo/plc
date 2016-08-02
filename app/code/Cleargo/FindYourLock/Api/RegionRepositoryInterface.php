<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Dealer region CRUD interface.
 * @api
 */
interface RegionRepositoryInterface
{
    /**
     * Save region.
     *
     * @param \Cleargo\FindYourLock\Api\Data\RegionInterface $region
     * @return \Cleargo\FindYourLock\Api\Data\RegionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Cleargo\FindYourLock\Api\Data\RegionInterface $region);

    /**
     * Retrieve region.
     *
     * @param int $regionId
     * @return \Cleargo\FindYourLock\Api\Data\RegionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($regionId);

    /**
     * Retrieve regions matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Cleargo\FindYourLock\Api\Data\RegionSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete region.
     *
     * @param \Cleargo\FindYourLock\Api\Data\RegionInterface $region
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Cleargo\FindYourLock\Api\Data\RegionInterface $region);

    /**
     * Delete region by ID.
     *
     * @param int $regionId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($regionId);
}
