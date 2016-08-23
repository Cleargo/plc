<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\Asiapay\Model\Ui;

use Magento\Framework\UrlInterface;
use Magento\Payment\Gateway\ConfigInterface;
use Magento\Checkout\Model\ConfigProviderInterface;

/**
 * Class ConfigProvider
 */
final class ConfigProvider implements ConfigProviderInterface
{
    const ASIAPAY_CODE = 'cleargo_asiapay';

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var \Cleargo\Asiapay\Helper\Data
     */
    protected $_dataHelper;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderFactory;

    /**
     * Order object
     *
     * @var \Magento\Sales\Model\Order
     */
    protected $_order;

    /**
     * Constructor
     *
     * @param \Cleargo\Asiapay\Helper\Data $dataHelper
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param ConfigInterface $config
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        \Cleargo\Asiapay\Helper\Data $dataHelper,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        ConfigInterface $config,
        UrlInterface $urlBuilder
    ) {
        $this->_dataHelper = $dataHelper;
        $this->_checkoutSession = $checkoutSession;
        $this->_orderFactory = $orderFactory;
        $this->config = $config;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'payment' => [
                self::ASIAPAY_CODE => [
                    'gatewayUrl' => $this->config->getValue('gateway_url'),
                    'configData' => $this->getConfigData(),
                ]
            ]
        ];
    }

    /**
     * Prepare Form Data
     *
     * @return mixed[]
     */
    public function getConfigData() {
        $merchant_id = $this->config->getValue('merchant_id');

        $payment_page_lang = $this->config->getValue('gateway_lang');

        $payment_type = $this->config->getValue('   payment_action');

        $payment_method_type = $this->config->getValue('payment_method_type');

        $payment_methods = $this->config->getValue('payment_method');

        $order_no_prefix = $this->config->getValue('order_no_prefix');

        $secureHashSecret = $this->config->getValue('secure_hash_secret');

        //$order = $this->_getOrder();
        //$currency_code = $order->getBaseCurrencyCode();

        $data = [];
        /*
        $data['orderRef'] = $order_no_prefix . $order->getIncrementId();
        $data['amount'] = sprintf('%.2f', $order->getBaseGrandTotal());
        $data['currCode'] = $this->_dataHelper->getIsoCurrCode($currency_code);
        */
        $data['successUrl'] = $this->urlBuilder->getUrl('asiapay/payment/success', ['_secure' => true]);
        $data['cancelUrl'] = $this->urlBuilder->getUrl('checkout/cart', ['_secure' => true]);
        $data['failUrl'] = $this->urlBuilder->getUrl('checkout/cart', ['_secure' => true]);
        $data['Lang'] = $payment_page_lang;
        $data['merchantId'] = $merchant_id;
        $data['payType'] = $payment_type;
        if($payment_method_type == "ALL" || empty($payment_methods)) {
            $data['payMethod'] = $payment_method_type;
        } else {
            $data['payMethod'] = implode('|', explode(',',$payment_methods));
        }
        //$data['secureHash'] = $this->_dataHelper->generatePaymentSecureHash($data['merchantId'], $data['orderRef'], $data['currCode'], $data['amount'], $data['payType'], $secureHashSecret);

        return $data;
    }

    /**
     * Get order object
     *
     * @return \Magento\Sales\Model\Order
     */
    /*
    protected function _getOrder()
    {
        if (!$this->_order) {
            $incrementId = $this->_getCheckout()->getLastRealOrderId();
            $this->_order = $this->_orderFactory->create()->loadByIncrementId($incrementId);
        }
        return $this->_order;
    }
    */

    /**
     * Get frontend checkout session object
     *
     * @return \Magento\Checkout\Model\Session
     */
    /*
    protected function _getCheckout()
    {
        return $this->_checkoutSession;
    }
    */
}
