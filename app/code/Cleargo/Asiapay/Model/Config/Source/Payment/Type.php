<?php
/**
 * Copyright ? 2016 CLEARgo. All rights reserved.
 */

namespace Cleargo\Asiapay\Model\Config\Source\Payment;


class Type implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'N', 'label' => __('Authorize and Capture')],
            ['value' => 'H', 'label' => __('Authorize Only')]
        ];
    }
}