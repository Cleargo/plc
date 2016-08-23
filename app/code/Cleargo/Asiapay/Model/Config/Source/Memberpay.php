<?php
/**
 * Copyright ? 2016 CLEARgo. All rights reserved.
 */

namespace Cleargo\Asiapay\Model\Config\Source;


class Memberpay implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'T', 'label' => __('Enabled')],
            ['value' => 'F', 'label' => __('Disabled')]
        ];
    }
}