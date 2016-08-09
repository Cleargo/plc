<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\PopupForm\Model;

use Cleargo\PopupForm\Api\Data\InquiryInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * CMS block model
 *
 * @method \Cleargo\PopupForm\Model\ResourceModel\Inquiry _getResource()
 * @method \Cleargo\PopupForm\Model\ResourceModel\Inquiry getResource()
 */
class Inquiry extends \Magento\Framework\Model\AbstractModel implements InquiryInterface, IdentityInterface
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    /**
     * CMS block cache tag
     */
    const CACHE_TAG = 'customer_inquiry';

    /**
     * @var string
     */
    protected $_cacheTag = 'customer_inquiry';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'customer_inquiry';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Cleargo\PopupForm\Model\ResourceModel\Inquiry');
    }

    /**
     * Prepare warranty's statuses.
     * Available event warranty_warranty_get_available_statuses to customize statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
    /**
     * Prevent blocks recursion
     *
     * @return \Magento\Framework\Model\AbstractModel
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSave()
    {
        $needle = 'inquiry_id="' . $this->getId() . '"';
        if (false == strstr($this->getContent(), $needle)) {
            return parent::beforeSave();
        }
        throw new \Magento\Framework\Exception\LocalizedException(
            __('Make sure that static block content does not reference the block itself.')
        );
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Retrieve block id
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::INQUIRY_ID);
    }

    /**
     * Retrieve block creation time
     *
     * @return string
     */
    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

    /**
     * Retrieve block update time
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
     * @return InquiryInterface
     */
    public function setId($id)
    {
        return $this->setData(self::INQUIRY_ID, $id);
    }


    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return InquiryInterface
     */
    public function setCreationTime($creationTime)
    {
        return $this->setData(self::CREATION_TIME, $creationTime);
    }

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return InquiryInterface
     */
    public function setUpdateTime($updateTime)
    {
        return $this->setData(self::UPDATE_TIME, $updateTime);
    }

    /**
     * Set is active
     *
     * @param bool|int $isActive
     * @return InquiryInterface
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }
}
