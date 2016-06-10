<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * District district CRUD interface.
 * @api
 */
interface DistrictRepositoryInterface
{
    /**
     * Save district.
     *
     * @param \Cleargo\FindYourLock\Api\Data\DistrictInterface $district
     * @return \Cleargo\FindYourLock\Api\Data\DistrictInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Cleargo\FindYourLock\Api\Data\DistrictInterface $district);

    /**
     * Retrieve district.
     *
     * @param int $districtId
     * @return \Cleargo\FindYourLock\Api\Data\DistrictInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($districtId);

    /**
     * Retrieve districts matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Cleargo\FindYourLock\Api\Data\DistrictSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete district.
     *
     * @param \Cleargo\FindYourLock\Api\Data\DistrictInterface $district
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Cleargo\FindYourLock\Api\Data\DistrictInterface $district);

    /**
     * Delete district by ID.
     *
     * @param int $districtId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($districtId);
}
