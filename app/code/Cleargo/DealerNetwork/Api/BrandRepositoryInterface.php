<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Dealer brand CRUD interface.
 * @api
 */
interface BrandRepositoryInterface
{
    /**
     * Save brand.
     *
     * @param \Cleargo\DealerNetwork\Api\Data\BrandInterface $brand
     * @return \Cleargo\DealerNetwork\Api\Data\BrandInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Cleargo\DealerNetwork\Api\Data\BrandInterface $brand);

    /**
     * Retrieve brand.
     *
     * @param int $brandId
     * @return \Cleargo\DealerNetwork\Api\Data\BrandInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($brandId);

    /**
     * Retrieve brands matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Cleargo\DealerNetwork\Api\Data\BrandSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete brand.
     *
     * @param \Cleargo\DealerNetwork\Api\Data\BrandInterface $brand
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Cleargo\DealerNetwork\Api\Data\BrandInterface $brand);

    /**
     * Delete brand by ID.
     *
     * @param int $brandId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($brandId);
}
