<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Model\Region\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive 
 */
class IsActive implements OptionSourceInterface
{
    /**
     * @var \Cleargo\FindYourLock\Model\Region
     */
    protected $lockRegion;

    /**
     * Constructor
     *
     * @param \Cleargo\FindYourLock\Model\Region $lockRegion
     */
    public function __construct(\Cleargo\FindYourLock\Model\Region $lockRegion)
    {
        $this->lockRegion = $lockRegion;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->lockRegion->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
