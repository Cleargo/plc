<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\Warranty\Model\Warranty\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive 
 */
class ProductType implements OptionSourceInterface
{
    /**
     * @var \Cleargo\Warranty\Model\Warranty
     */
    protected $warranty;

    /**
     * Constructor
     *
     * @param \Cleargo\Warranty\Model\Warranty $warranty
     */
    public function __construct(\Cleargo\Warranty\Model\Warranty $warranty)
    {
        $this->warranty = $warranty;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->warranty->getAvailableProductTypes();
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
