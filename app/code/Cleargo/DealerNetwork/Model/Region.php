<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Model;

use Cleargo\DealerNetwork\Api\Data\RegionInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Dealer Region Model
 *
 * @method \Cleargo\DealerNetwork\Model\ResourceModel\Region _getResource()
 * @method \Cleargo\DealerNetwork\Model\ResourceModel\Region getResource()
 */
class Region extends \Magento\Framework\Model\AbstractModel implements RegionInterface, IdentityInterface
{
    /**#@+
     * Region's Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    /**#@-*/

    /**
     * Dealer region cache tag
     */
    const CACHE_TAG = 'dealer_region';

    /**
     * @var string
     */
    protected $_cacheTag = 'dealer_region';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'dealer_region';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Cleargo\DealerNetwork\Model\ResourceModel\Region');
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
     * Receive region store ids
     *
     * @return int[]
     */
    public function getStores()
    {
        return $this->hasData('stores') ? $this->getData('stores') : $this->getData('store_id');
    }

    /**
     * Check if region identifier exist for specific store
     * return region id if region exists
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
     * Prepare region's statuses.
     * Available event dealer_region_get_available_statuses to customize statuses.
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
        return parent::getData(self::REGION_ID);
    }

    /**
     * Get country id
     *
     * @return string
     */
    public function getCountryId()
    {
        return $this->getData(self::COUNTRY_ID);
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
     * Is shown on frontend
     *
     * @return bool
     */
    public function isShownFrontend()
    {
        return (bool)$this->getData(self::IS_SHOWN_FRONTEND);
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
     * @return \Cleargo\DealerNetwork\Api\Data\RegionInterface
     */
    public function setId($id)
    {
        return $this->setData(self::REGION_ID, $id);
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return \Cleargo\DealerNetwork\Api\Data\RegionInterface
     */
    public function setIdentifier($identifier)
    {
        return $this->setData(self::IDENTIFIER, $identifier);
    }

    /**
     * Set name
     *
     * @param string $name
     * @return \Cleargo\DealerNetwork\Api\Data\RegionInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return \Cleargo\DealerNetwork\Api\Data\RegionInterface
     */
    public function setCreationTime($creationTime)
    {
        return $this->setData(self::CREATION_TIME, $creationTime);
    }

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return \Cleargo\DealerNetwork\Api\Data\RegionInterface
     */
    public function setUpdateTime($updateTime)
    {
        return $this->setData(self::UPDATE_TIME, $updateTime);
    }

    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return \Cleargo\DealerNetwork\Api\Data\RegionInterface
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }
}
