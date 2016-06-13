<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Api\Data;

/**
 * Lock dealer interface.
 * @api
 */
interface LockInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const LOCK_ID                  = 'lock_id'; 
    const DISTRICT_ID            = 'district_id';
    const IDENTIFIER               = 'identifier';
    const NAME                    = 'name';
    const NAME2                    = 'name2';
    const ADDRESS                    = 'address'; 
    const YEAR                    = 'year';
    const DEVELOPER                    = 'developer';
    const UNIT                    = 'unit';
    const UNIT_PER_FLOOR                    = 'unit_per_floor';
    const HEIGHT                    = 'height';
    const SIZE                    = 'size';
    const BRAND                    = 'brand';
    const THINKNESS                    = 'thickness';
    const BLOCK                    = 'block';
    const BACKSET                    = 'backset';
    const LOCKSET                    = 'lockset';
    const CYLINDER                    = 'cylinder';
    const PRODUCT_ID                    = 'product_id';
    const BEFORE_IMAGE1                    = 'before_image1';
    const BEFORE_IMAGE2                    = 'before_image2';
    const AFTER_IMAGE1                    = 'after_image1';
    const AFTER_IMAGE2                    = 'after_image2';
    const LOGO                    = 'logo';
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
    public function getDistrictId();

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
     * @return \Cleargo\FindYourLock\Api\Data\LockInterface
     */
    public function setId($id);

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return \Cleargo\FindYourLock\Api\Data\LockInterface
     */
    public function setIdentifier($identifier);

    /**
     * Set name
     *
     * @param string $name
     * @return \Cleargo\FindYourLock\Api\Data\LockInterface
     */
    public function setName($name);

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return \Cleargo\FindYourLock\Api\Data\LockInterface
     */
    public function setCreationTime($creationTime);

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return \Cleargo\FindYourLock\Api\Data\LockInterface
     */
    public function setUpdateTime($updateTime);

    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return \Cleargo\FindYourLock\Api\Data\LockInterface
     */
    public function setIsActive($isActive);
}
