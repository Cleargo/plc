<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Lock dealer CRUD interface.
 * @api
 */
interface LockRepositoryInterface
{
    /**
     * Save dealer.
     *
     * @param \Cleargo\FindYourLock\Api\Data\LockInterface $lock
     * @return \Cleargo\FindYourLock\Api\Data\LockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Cleargo\FindYourLock\Api\Data\LockInterface $lock);

    /**
     * Retrieve dealer.
     *
     * @param int $lockId
     * @return \Cleargo\FindYourLock\Api\Data\LockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($lockId);

    /**
     * Retrieve dealers matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Cleargo\FindYourLock\Api\Data\LockSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete dealer.
     *
     * @param \Cleargo\FindYourLock\Api\Data\LockInterface $lock
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Cleargo\FindYourLock\Api\Data\LockInterface $lock);

    /**
     * Delete dealer by ID.
     *
     * @param int $lockId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($lockId);
}
