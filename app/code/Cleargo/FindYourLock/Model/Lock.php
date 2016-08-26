<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 */
namespace Cleargo\FindYourLock\Model;

use Cleargo\FindYourLock\Api\Data\LockInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Lock Lock Model
 *
 * @method \Cleargo\FindYourLock\Model\ResourceModel\Lock _getResource()
 * @method \Cleargo\FindYourLock\Model\ResourceModel\Lock getResource()
 */
class Lock extends \Magento\Framework\Model\AbstractModel implements LockInterface, IdentityInterface
{
    /**#@+
     * Lock's Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    /**#@-*/

    /**
     * Lock dealer cache tag
     */
    const CACHE_TAG = 'lock_lock';

    /**
     * @var string
     */
    protected $_cacheTag = 'lock_lock';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'lock_lock';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Cleargo\FindYourLock\Model\ResourceModel\Lock');
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
     * Receive dealer store ids
     *
     * @return int[]
     */
    public function getStores()
    {
        return $this->hasData('stores') ? $this->getData('stores') : $this->getData('store_id');
    }

    /**
     * Check if dealer identifier exist for specific store
     * return dealer id if dealer exists
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
     * Prepare dealer's statuses.
     * Available event lock_lock_get_available_statuses to customize statuses.
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
        return parent::getData(self::LOCK_ID);
    }

    /**
     * Get region id
     *
     * @return string
     */
    public function getDistrictId()
    {
        return $this->getData(self::DISTRICT_ID);
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
     * Get name2
     *
     * @return string
     */
    public function getName2()
    {
        return $this->getData(self::NAME2);
    }
    /**
     * Get ADDRESS
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->getData(self::ADDRESS);
    }
public function getYear()
    {
        return $this->getData(self::YEAR);
    }
public function getDeveloper()
    {
        return $this->getData(self::DEVELOPER);
    }
public function getUnit()
    {
        return $this->getData(self::UNIT);
    }
public function getUnitPerFloor()
    {
        return $this->getData(self::UNIT_PER_FLOOR);
    }
public function getHeight()
    {
        return $this->getData(self::HEIGHT);
    }
    public function getSize()
    {
        return $this->getData(self::SIZE);
    }

    public function getBrand()
    {
        return $this->getData(self::BRAND);
    }
    public function getThickness()
    {
        return $this->getData(self::THINKNESS);
    }
    public function getBlock()
    {
        return $this->getData(self::BLOCK);
    }
    public function getBackset()
    {
        return $this->getData(self::BACKSET);
    }
    public function getLockset()
    {
        return $this->getData(self::LOCKSET);
    }
    public function getCylinder()
    {
        return $this->getData(self::CYLINDER);
    }
    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }
    public function getLogo()
    {
        if(!$this->getData(self::LOGO)){
            return false;
        }
        return 'http://plc.dev.cleargo.com/pub/media/'.$this->getData(self::LOGO);
    }
    public function getBeforeImage1()
    {
        if(!$this->getData(self::BEFORE_IMAGE1)){
            return false;
        }
        return $this->getData(self::BEFORE_IMAGE1)?'http://plc.dev.cleargo.com/pub/media/'.$this->getData(self::BEFORE_IMAGE1):NULL;
    }
    public function getBeforeImage2()
    {
        if(!$this->getData(self::BEFORE_IMAGE2)){
            return false;
        }
        return $this->getData(self::BEFORE_IMAGE2)?'http://plc.dev.cleargo.com/pub/media/'.$this->getData(self::BEFORE_IMAGE2):NULL;
    }
    public function getAfterImage1()
    {
        if(!$this->getData(self::AFTER_IMAGE1)){
            return false;
        }
        return $this->getData(self::AFTER_IMAGE1)?'http://plc.dev.cleargo.com/pub/media/'.$this->getData(self::AFTER_IMAGE1):NULL;
    }
    public function getAfterImage2()
    {
        if(!$this->getData(self::AFTER_IMAGE2)){
            return false;
        }
        return $this->getData(self::AFTER_IMAGE2)?'http://plc.dev.cleargo.com/pub/media/'.$this->getData(self::AFTER_IMAGE2):NULL;
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
     * @return \Cleargo\FindYourLock\Api\Data\LockInterface
     */
    public function setId($id)
    {
        return $this->setData(self::LOCK_ID, $id);
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return \Cleargo\FindYourLock\Api\Data\LockInterface
     */
    public function setIdentifier($identifier)
    {
        return $this->setData(self::IDENTIFIER, $identifier);
    }

    /**
     * Set name
     *
     * @param string $name
     * @return \Cleargo\FindYourLock\Api\Data\LockInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return \Cleargo\FindYourLock\Api\Data\LockInterface
     */
    public function setCreationTime($creationTime)
    {
        return $this->setData(self::CREATION_TIME, $creationTime);
    }

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return \Cleargo\FindYourLock\Api\Data\LockInterface
     */
    public function setUpdateTime($updateTime)
    {
        return $this->setData(self::UPDATE_TIME, $updateTime);
    }

    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return \Cleargo\FindYourLock\Api\Data\LockInterface
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }
}
