<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 */
namespace Cleargo\FindYourLock\Model;

use Cleargo\FindYourLock\Api\Data\DistrictInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * District District Model
 *
 * @method \Cleargo\FindYourLock\Model\ResourceModel\District _getResource()
 * @method \Cleargo\FindYourLock\Model\ResourceModel\District getResource()
 */
class District extends \Magento\Framework\Model\AbstractModel implements DistrictInterface, IdentityInterface
{
    /**#@+
     * District's Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    /**#@-*/

    /**
     * District district cache tag
     */
    const CACHE_TAG = 'lock_district';

    /**
     * @var string
     */
    protected $_cacheTag = 'lock_district';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'lock_district';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Cleargo\FindYourLock\Model\ResourceModel\District');
    }

    /**
     * Load object data
     *
     * @param int|null $id
     * @param string $field
     * @return $this
     */
    public function load($id, $field = null)
    {
        return parent::load($id, $field);
    }

    /**
     * Receive district store ids
     *
     * @return int[]
     */
    public function getStores()
    {
        return $this->hasData('stores') ? $this->getData('stores') : $this->getData('store_id');
    }

    /**
     * Check if district identifier exist for specific store
     * return district id if district exists
     *
     * @param string $identifier
     * @param int $storeId
     * @return int
     */
    public function checkIdentifier($identifier, $storeId)
    {
        return $this->_getResource()->checkIdentifier($identifier, $storeId);
    }

    /**
     * Prepare district's statuses.
     * Available event lock_district_get_available_statuses to customize statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return parent::getData(self::DISTRICT_ID);
    }

    /**
     * Get region id
     *
     * @return string
     */
    public function getRegionId()
    {
        return $this->getData(self::REGION_ID);
    }

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getData(self::IDENTIFIER);
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * Get sort order
     *
     * @return int|null
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * Get creation time
     *
     * @return string
     */
    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

    /**
     * Get update time
     *
     * @return string
     */
    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    /**
     * Is active
     *
     * @return bool
     */
    public function isActive()
    {
        return (bool)$this->getData(self::IS_ACTIVE);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return \Cleargo\FindYourLock\Api\Data\DistrictInterface
     */
    public function setId($id)
    {
        return $this->setData(self::DISTRICT_ID, $id);
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return \Cleargo\FindYourLock\Api\Data\DistrictInterface
     */
    public function setIdentifier($identifier)
    {
        return $this->setData(self::IDENTIFIER, $identifier);
    }

    /**
     * Set name
     *
     * @param string $name
     * @return \Cleargo\FindYourLock\Api\Data\DistrictInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return \Cleargo\FindYourLock\Api\Data\DistrictInterface
     */
    public function setCreationTime($creationTime)
    {
        return $this->setData(self::CREATION_TIME, $creationTime);
    }

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return \Cleargo\FindYourLock\Api\Data\DistrictInterface
     */
    public function setUpdateTime($updateTime)
    {
        return $this->setData(self::UPDATE_TIME, $updateTime);
    }

    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return \Cleargo\FindYourLock\Api\Data\DistrictInterface
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }
}
