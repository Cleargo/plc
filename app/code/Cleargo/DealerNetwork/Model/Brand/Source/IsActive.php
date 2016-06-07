<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Model\Brand\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class IsActive implements OptionSourceInterface
{
    /**
     * @var \Cleargo\DealerNetwork\Model\Brand
     */
    protected $dealerBrand;

    /**
     * Constructor
     *
     * @param \Cleargo\DealerNetwork\Model\Brand $dealerBrand
     */
    public function __construct(\Cleargo\DealerNetwork\Model\Brand $dealerBrand)
    {
        $this->dealerBrand = $dealerBrand;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->dealerBrand->getAvailableStatuses();
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
