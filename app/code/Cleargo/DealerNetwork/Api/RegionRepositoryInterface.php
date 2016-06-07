<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Api;

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
     * @param \Cleargo\DealerNetwork\Api\Data\RegionInterface $region
     * @return \Cleargo\DealerNetwork\Api\Data\RegionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Cleargo\DealerNetwork\Api\Data\RegionInterface $region);

    /**
     * Retrieve region.
     *
     * @param int $regionId
     * @return \Cleargo\DealerNetwork\Api\Data\RegionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($regionId);

    /**
     * Retrieve regions matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Cleargo\DealerNetwork\Api\Data\RegionSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete region.
     *
     * @param \Cleargo\DealerNetwork\Api\Data\RegionInterface $region
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Cleargo\DealerNetwork\Api\Data\RegionInterface $region);

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
