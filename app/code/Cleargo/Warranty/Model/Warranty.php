<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 */
namespace Cleargo\Warranty\Model;

use Cleargo\Warranty\Api\Data\WarrantyInterface;

/**
 * District Warranty Model
 *
 * @method \Cleargo\Warranty\Model\ResourceModel\Warranty _getResource()
 * @method \Cleargo\Warranty\Model\ResourceModel\Warranty getResource()
 */
class Warranty extends \Magento\Framework\Model\AbstractModel implements WarrantyInterface
{
    /**#@+
     * Warranty's Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    /**#@-*/


    /**
     * District warranty cache tag
     */
    const CACHE_TAG = 'warranty_warranty';

    /**
     * @var string
     */
    protected $_cacheTag = 'warranty_warranty';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'warranty_warranty';

    /**
     * Initialize resource model
     *
     * @return void
     */
    private static $QUESTIONS = null;

    public static function QUESTIONS() {
        if (self::$QUESTIONS == null) {
            self::$QUESTIONS = array(
                1 => __('Who is your favourite singer?'),
                2 => __('What is your favourite pastime?'),
                3 => __('What is your favourite sports team?'),
                4 => __('What is the name of your primary school?'),
                5 => __('What is your pet’s name?'),
                6 => __('What colour do you like best?'),
                7 => __('Which is your favourite festival?'),
                8 => __('What is your favourite fruit?')
            );
        }
        return self::$QUESTIONS;
    }

    protected function _construct()
    {
        $this->_init('Cleargo\Warranty\Model\ResourceModel\Warranty');
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
     * Prepare warranty's security questions.
     * Available event warranty_warranty_get_available_statuses to customize questions.
     *
     * @return array
     */

    public function getAvailableQuestions()
    {
        return Warranty::QUESTIONS();
    }

    /**
     * Prepare warranty's security product types.
     * Available event warranty_warranty_get_available_statuses to customize product types.
     *
     * @return array
     */

    public function getAvailableProductTypes()
    {
        return [
            1 => __('Auxiliary Lock'),
            2 => __('Cylinder'),
            3 => __('Entrance Lockset'),
            4 => __('Others'),
        ];
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
     * @return \Cleargo\Warranty\Api\Data\WarrantyInterface
     */
    public function setId($id)
    {
        return $this->setData(self::REGION_ID, $id);
    }


    /**
     * Set name
     *
     * @param string $name
     * @return \Cleargo\Warranty\Api\Data\WarrantyInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return \Cleargo\Warranty\Api\Data\WarrantyInterface
     */
    public function setCreationTime($creationTime)
    {
        return $this->setData(self::CREATION_TIME, $creationTime);
    }

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return \Cleargo\Warranty\Api\Data\WarrantyInterface
     */
    public function setUpdateTime($updateTime)
    {
        return $this->setData(self::UPDATE_TIME, $updateTime);
    }

    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return \Cleargo\Warranty\Api\Data\WarrantyInterface
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }
}
