<?php
/**
 *
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\Asiapay\Controller\Payment;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultFactory;

class Ajax extends Action
{
    /**
     * Config path to merchand ID settings
     */
    const XML_PATH_MERCHANT_ID = 'payment/cleargo_asiapay/merchant_id';

    /**
     * Config path to payment type settings
     */
    const XML_PATH_PAYMENT_TYPE = 'payment/cleargo_asiapay/payment_action';

    /**
     * Config path to order no prefix settings
     */
    const XML_PATH_ORDER_NO_PREFIX = 'payment/cleargo_asiapay/order_no_prefix';

    /**
     * Config path to secure hash secret settings
     */
    const XML_PATH_SECURE_HASH_SECRET = 'payment/cleargo_asiapay/secure_hash_secret';

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
     * Ajax constructor.
     * @param Context $context
     * @param \Cleargo\Asiapay\Helper\Data $dataHelper
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     */
    public function __construct(
        Context $context,
        \Cleargo\Asiapay\Helper\Data $dataHelper,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\OrderFactory $orderFactory
    ) {
        $this->_dataHelper = $dataHelper;
        $this->_checkoutSession = $checkoutSession;
        $this->_orderFactory = $orderFactory;
        parent::__construct($context);
    }

    /**
     * Redirect to the gateway
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $merchant_id = $this->_objectManager->get(
            'Magento\Framework\App\Config\ScopeConfigInterface',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        )->getValue(
            self::XML_PATH_MERCHANT_ID,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $payment_type = $this->_objectManager->get(
            'Magento\Framework\App\Config\ScopeConfigInterface',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        )->getValue(
            self::XML_PATH_PAYMENT_TYPE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $order_no_prefix = $this->_objectManager->get(
            'Magento\Framework\App\Config\ScopeConfigInterface',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        )->getValue(
            self::XML_PATH_ORDER_NO_PREFIX,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $secureHashSecret = $this->_objectManager->get(
            'Magento\Framework\App\Config\ScopeConfigInterface',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        )->getValue(
            self::XML_PATH_SECURE_HASH_SECRET,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        
        $order = $this->_getOrder();
        $currency_code = $order->getBaseCurrencyCode();

        $orderData = [];
        $orderData['orderRef'] = (($order_no_prefix && $order_no_prefix != "") ? $order_no_prefix . "-" : "") . $order->getIncrementId();
        $orderData['amount'] = sprintf('%.2f', $order->getBaseGrandTotal());
        $orderData['currCode'] = $this->_dataHelper->getIsoCurrCode($currency_code);
        $orderData['secureHash'] = $this->_dataHelper->generatePaymentSecureHash($merchant_id, $orderData['orderRef'], $orderData['currCode'], $orderData['amount'], $payment_type, $secureHashSecret);

        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($orderData);
        return $resultJson;
    }

    /**
     * Get order object
     *
     * @return \Magento\Sales\Model\Order
     */
    protected function _getOrder()
    {
        if (!$this->_order) {
            $incrementId = $this->_getCheckout()->getLastRealOrderId();
            $this->_order = $this->_orderFactory->create()->loadByIncrementId($incrementId);
        }
        return $this->_order;
    }

    /**
     * Get frontend checkout session object
     *
     * @return \Magento\Checkout\Model\Session
     */
    protected function _getCheckout()
    {
        return $this->_checkoutSession;
    }
}
