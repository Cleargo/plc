<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Api\Data;

/**
 * District district interface.
 * @api
 */
interface DistrictInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const DISTRICT_ID                  = 'district_id';
    const REGION_ID      = 'region_id';
    const IDENTIFIER               = 'identifier';
    const NAME                    = 'name';
    const SORT_ORDER                    = 'sort_order';
    const CREATION_TIME            = 'creation_time';
    const UPDATE_TIME              = 'update_time';
    const IS_ACTIVE                = 'is_active';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get region id
     *
     * @return int
     */
    public function getRegionId();

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
     * Is active
     *
     * @return bool|null
     */
    public function isActive();

    /**
     * Set ID
     *
     * @param int $id
     * @return \Cleargo\FindYourLock\Api\Data\DistrictInterface
     */
    public function setId($id);

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return \Cleargo\FindYourLock\Api\Data\DistrictInterface
     */
    public function setIdentifier($identifier);

    /**
     * Set name
     *
     * @param string $name
     * @return \Cleargo\FindYourLock\Api\Data\DistrictInterface
     */
    public function setName($name);

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return \Cleargo\FindYourLock\Api\Data\DistrictInterface
     */
    public function setCreationTime($creationTime);

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return \Cleargo\FindYourLock\Api\Data\DistrictInterface
     */
    public function setUpdateTime($updateTime);

    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return \Cleargo\FindYourLock\Api\Data\DistrictInterface
     */
    public function setIsActive($isActive);
}
