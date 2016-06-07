<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Model\Dealer\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class IsActive implements OptionSourceInterface
{
    /**
     * @var \Cleargo\DealerNetwork\Model\Dealer
     */
    protected $dealerDealer;

    /**
     * Constructor
     *
     * @param \Cleargo\DealerNetwork\Model\Dealer $dealerDealer
     */
    public function __construct(\Cleargo\DealerNetwork\Model\Dealer $dealerDealer)
    {
        $this->dealerDealer = $dealerDealer;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->dealerDealer->getAvailableStatuses();
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
