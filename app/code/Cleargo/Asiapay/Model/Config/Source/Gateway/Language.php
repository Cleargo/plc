<?php
/**
 * Copyright ? 2016 CLEARgo. All rights reserved.
 */

namespace Cleargo\Asiapay\Model\Config\Source\Gateway;


class Language implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'E', 'label' => __('English')],
            ['value' => 'C', 'label' => __('Traditional Chinese')],
            ['value' => 'X', 'label' => __('Simplified Chinese')],
            ['value' => 'J', 'label' => __('Japanese')],
            ['value' => 'T', 'label' => __('Thai')],
            ['value' => 'F', 'label' => __('French')],
            ['value' => 'G', 'label' => __('German')],
            ['value' => 'R', 'label' => __('Russian')],
            ['value' => 'S', 'label' => __('Spanish')],
            ['value' => 'V', 'label' => __('Vietnamese')],
        ];
    }
}