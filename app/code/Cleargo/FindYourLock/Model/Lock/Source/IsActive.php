<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Model\Lock\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class IsActive implements OptionSourceInterface
{
    /**
     * @var \Cleargo\FindYourLock\Model\Lock
     */
    protected $lockLock;

    /**
     * Constructor
     *
     * @param \Cleargo\FindYourLock\Model\Lock $lockLock
     */
    public function __construct(\Cleargo\FindYourLock\Model\Lock $lockLock)
    {
        $this->lockLock = $lockLock;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->lockLock->getAvailableStatuses();
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
