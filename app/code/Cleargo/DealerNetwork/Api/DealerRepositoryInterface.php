<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Dealer dealer CRUD interface.
 * @api
 */
interface DealerRepositoryInterface
{
    /**
     * Save dealer.
     *
     * @param \Cleargo\DealerNetwork\Api\Data\DealerInterface $dealer
     * @return \Cleargo\DealerNetwork\Api\Data\DealerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Cleargo\DealerNetwork\Api\Data\DealerInterface $dealer);

    /**
     * Retrieve dealer.
     *
     * @param int $dealerId
     * @return \Cleargo\DealerNetwork\Api\Data\DealerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($dealerId);

    /**
     * Retrieve dealers matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Cleargo\DealerNetwork\Api\Data\DealerSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete dealer.
     *
     * @param \Cleargo\DealerNetwork\Api\Data\DealerInterface $dealer
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Cleargo\DealerNetwork\Api\Data\DealerInterface $dealer);

    /**
     * Delete dealer by ID.
     *
     * @param int $dealerId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($dealerId);
}
