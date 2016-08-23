<?php
/**
 * Copyright ? 2016 CLEARgo. All rights reserved.
 */

namespace Cleargo\Asiapay\Model\Config\Source\Payment\Method;


class Allspecific implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'ALL', 'label' => __('All')],
            ['value' => 'SPECIFIC', 'label' => __('Specific methods')]
        ];
    }
}