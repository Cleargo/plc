<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Model\Country\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class IsActive implements OptionSourceInterface
{
    /**
     * @var \Cleargo\DealerNetwork\Model\Country
     */
    protected $dealerCountry;

    /**
     * Constructor
     *
     * @param \Cleargo\DealerNetwork\Model\Country $dealerCountry
     */
    public function __construct(\Cleargo\DealerNetwork\Model\Country $dealerCountry)
    {
        $this->dealerCountry = $dealerCountry;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->dealerCountry->getAvailableStatuses();
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
