<?php
/**
 * Copyright ? 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\Asiapay\Model;

class Payment extends \Magento\Payment\Model\Method\AbstractMethod
{
    const PAYMENT_METHOD_ASIAPAY_CODE = 'cleargo_asiapay';

    /**
     * Payment method code
     *
     * @var string
     */
    protected $_code = self::PAYMENT_METHOD_ASIAPAY_CODE;
}
