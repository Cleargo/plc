<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\Warranty\Api\Data;

/**
 * District warranty interface.
 * @api
 */
interface WarrantyInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const REGION_ID                  = 'warranty_id';
    const CREATION_TIME            = 'creation_time';
    const UPDATE_TIME              = 'update_time';
    const IS_ACTIVE                = 'status';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();




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
     * @return \Cleargo\Warranty\Api\Data\WarrantyInterface
     */
    public function setId($id);



    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return \Cleargo\Warranty\Api\Data\WarrantyInterface
     */
    public function setCreationTime($creationTime);

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return \Cleargo\Warranty\Api\Data\WarrantyInterface
     */
    public function setUpdateTime($updateTime);

    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return \Cleargo\Warranty\Api\Data\WarrantyInterface
     */
    public function setIsActive($isActive);
}
