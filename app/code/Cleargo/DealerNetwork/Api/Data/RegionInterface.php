<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Api\Data;

/**
 * Dealer region interface.
 * @api
 */
interface RegionInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const REGION_ID                  = 'region_id';
    const COUNTRY_ID      = 'country_id';
    const IDENTIFIER               = 'identifier';
    const NAME                    = 'name';
    const SORT_ORDER                    = 'sort_order';
    const CREATION_TIME            = 'creation_time';
    const UPDATE_TIME              = 'update_time';
    const IS_SHOWN_FRONTEND      = 'is_shown_frontend';
    const IS_ACTIVE                = 'is_active';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get country id
     *
     * @return int
     */
    public function getCountryId();

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Get sort order
     *
     * @return int|null
     */
    public function getSortOrder();

    /**
     * Get creation time
     *
     * @return string|null
     */
    public function getCreationTime();

    /**
     * Get update time
     *
     * @return string|null
     */
    public function getUpdateTime();

    /**
     * Is shown on frontend
     *
     * @return bool|null
     */
    public function isShownFrontend();
    
    /**
     * Is active
     *
     * @return bool|null
     */
    public function isActive();

    /**
     * Set ID
     *
     * @param int $id
     * @return \Cleargo\DealerNetwork\Api\Data\RegionInterface
     */
    public function setId($id);

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return \Cleargo\DealerNetwork\Api\Data\RegionInterface
     */
    public function setIdentifier($identifier);

    /**
     * Set name
     *
     * @param string $name
     * @return \Cleargo\DealerNetwork\Api\Data\RegionInterface
     */
    public function setName($name);

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return \Cleargo\DealerNetwork\Api\Data\RegionInterface
     */
    public function setCreationTime($creationTime);

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return \Cleargo\DealerNetwork\Api\Data\RegionInterface
     */
    public function setUpdateTime($updateTime);

    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return \Cleargo\DealerNetwork\Api\Data\RegionInterface
     */
    public function setIsActive($isActive);
}
