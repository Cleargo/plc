<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Model\District\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class IsActive implements OptionSourceInterface
{
    /**
     * @var \Cleargo\FindYourLock\Model\District
     */
    protected $lockDistrict;

    /**
     * Constructor
     *
     * @param \Cleargo\FindYourLock\Model\District $lockDistrict
     */
    public function __construct(\Cleargo\FindYourLock\Model\District $lockDistrict)
    {
        $this->lockDistrict = $lockDistrict;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->lockDistrict->getAvailableStatuses();
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
