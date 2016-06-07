<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Model;

use Cleargo\DealerNetwork\Api\Data\CountryInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Dealer Country Model
 *
 * @method \Cleargo\DealerNetwork\Model\ResourceModel\Country _getResource()
 * @method \Cleargo\DealerNetwork\Model\ResourceModel\Country getResource()
 */
class Country extends \Magento\Framework\Model\AbstractModel implements CountryInterface, IdentityInterface
{
    /**#@+
     * Country's Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    /**#@-*/

    /**
     * Dealer country cache tag
     */
    const CACHE_TAG = 'dealer_country';

    /**
     * @var string
     */
    protected $_cacheTag = 'dealer_country';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'dealer_country';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Cleargo\DealerNetwork\Model\ResourceModel\Country');
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
     * Receive country store ids
     *
     * @return int[]
     */
    public function getStores()
    {
        return $this->hasData('stores') ? $this->getData('stores') : $this->getData('store_id');
    }

    /**
     * Check if country identifier exist for specific store
     * return country id if country exists
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
     * Prepare country's statuses.
     * Available event dealer_country_get_available_statuses to customize statuses.
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
        return parent::getData(self::COUNTRY_ID);
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
     * @return \Cleargo\DealerNetwork\Api\Data\CountryInterface
     */
    public function setId($id)
    {
        return $this->setData(self::COUNTRY_ID, $id);
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return \Cleargo\DealerNetwork\Api\Data\CountryInterface
     */
    public function setIdentifier($identifier)
    {
        return $this->setData(self::IDENTIFIER, $identifier);
    }

    /**
     * Set name
     *
     * @param string $name
     * @return \Cleargo\DealerNetwork\Api\Data\CountryInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return \Cleargo\DealerNetwork\Api\Data\CountryInterface
     */
    public function setCreationTime($creationTime)
    {
        return $this->setData(self::CREATION_TIME, $creationTime);
    }

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return \Cleargo\DealerNetwork\Api\Data\CountryInterface
     */
    public function setUpdateTime($updateTime)
    {
        return $this->setData(self::UPDATE_TIME, $updateTime);
    }

    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return \Cleargo\DealerNetwork\Api\Data\CountryInterface
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }
}
