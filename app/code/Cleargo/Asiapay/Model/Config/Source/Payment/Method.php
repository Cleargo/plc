<?php
/**
 * Copyright ? 2016 CLEARgo. All rights reserved.
 */

namespace Cleargo\Asiapay\Model\Config\Source\Payment;


class Method implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'CC', 'label' => __('Credit Card')],
            ['value' => 'VISA', 'label' => __('Visa')],
            ['value' => 'Master', 'label' => __('MasterCard')],
            ['value' => 'JCB', 'label' => __('JCB')],
            ['value' => 'Diners', 'label' => __('Diners')],
            ['value' => 'PPS', 'label' => __('PPS')],
            ['value' => 'PAYPAL', 'label' => __('PayPal')],
            ['value' => 'CHINAPAY', 'label' => __('China UnionPay')],
            ['value' => 'ALIPAY', 'label' => __('ALIPAY')],
            ['value' => '99BILL', 'label' => __('99BILL')],
            ['value' => 'MEPS', 'label' => __('MEPS')],
            ['value' => 'SCB', 'label' => __('SCB')],
            ['value' => 'BPM', 'label' => __('Bill Payment')],
            ['value' => 'KTB', 'label' => __('Krung thai Bank')],
            ['value' => 'UOB', 'label' => __('United Oversea bank')],
            ['value' => 'KRUNGSRIONLINE', 'label' => __('Bank of Ayudhya')],
            ['value' => 'TMB', 'label' => __('TMB Bank')],
            ['value' => 'IBANKING', 'label' => __('Bangkok Bank iBanking')],
            ['value' => 'UPOP', 'label' => __('UPOP')],
            ['value' => 'M2U', 'label' => __('M2U')],
            ['value' => 'CIMBCLICK', 'label' => __('CIMBCLICK')],
            ['value' => 'OCTOPUS', 'label' => __('OCTOPUS')],
            ['value' => 'WECHAT', 'label' => __('WECHAT')],
            ['value' => 'ONEPAY', 'label' => __('ONEPAY')],
            ['value' => 'VCO', 'label' => __('VISA Checkout')],
            ['value' => 'MP', 'label' => __('MasterPass')]
        ];
    }
}