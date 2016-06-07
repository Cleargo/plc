<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Dealer country CRUD interface.
 * @api
 */
interface CountryRepositoryInterface
{
    /**
     * Save country.
     *
     * @param \Cleargo\DealerNetwork\Api\Data\CountryInterface $country
     * @return \Cleargo\DealerNetwork\Api\Data\CountryInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Cleargo\DealerNetwork\Api\Data\CountryInterface $country);

    /**
     * Retrieve country.
     *
     * @param int $countryId
     * @return \Cleargo\DealerNetwork\Api\Data\CountryInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($countryId);

    /**
     * Retrieve countrys matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Cleargo\DealerNetwork\Api\Data\CountrySearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete country.
     *
     * @param \Cleargo\DealerNetwork\Api\Data\CountryInterface $country
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Cleargo\DealerNetwork\Api\Data\CountryInterface $country);

    /**
     * Delete country by ID.
     *
     * @param int $countryId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($countryId);
}
