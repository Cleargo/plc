<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Model\Region\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class IsActive implements OptionSourceInterface
{
    /**
     * @var \Cleargo\DealerNetwork\Model\Region
     */
    protected $dealerRegion;

    /**
     * Constructor
     *
     * @param \Cleargo\DealerNetwork\Model\Region $dealerRegion
     */
    public function __construct(\Cleargo\DealerNetwork\Model\Region $dealerRegion)
    {
        $this->dealerRegion = $dealerRegion;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->dealerRegion->getAvailableStatuses();
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
