<?php
/**
 * Copyright ? 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\Asiapay\Block\Form;

class Redirect extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'form/redirect.phtml';

    /**
     * Config path to gateway URL settings
     */
    const XML_PATH_GATEWAY_URL = 'payment/cleargo_asiapay/gateway_url';

    /**
     * Config path to merchand ID settings
     */
    const XML_PATH_MERCHANT_ID = 'payment/cleargo_asiapay/merchant_id';

    /**
     * Config path to payment page language settings
     */
    const XML_PATH_GATEWAY_LANG = 'payment/cleargo_asiapay/gateway_lang';

    /**
     * Config path to payment type settings
     */
    const XML_PATH_PAYMENT_TYPE = 'payment/cleargo_asiapay/payment_action';

    /**
     * Config path to payment method type settings
     */
    const XML_PATH_PAYMENT_METHOD_TYPE = 'payment/cleargo_asiapay/payment_method_type';

    /**
     * Config path to payment methods settings
     */
    const XML_PATH_PAYMENT_METHOD = 'payment/cleargo_asiapay/payment_method';

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
     * Constructor
     *
     * @param \Cleargo\Asiapay\Helper\Data $dataHelper
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Cleargo\Asiapay\Helper\Data $dataHelper,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        $this->_dataHelper = $dataHelper;
        $this->_checkoutSession = $checkoutSession;
        $this->_orderFactory = $orderFactory;
        $this->validator = $context->getValidator();
        $this->resolver = $context->getResolver();
        $this->_filesystem = $context->getFilesystem();
        $this->templateEnginePool = $context->getEnginePool();
        $this->_storeManager = $context->getStoreManager();
        $this->_appState = $context->getAppState();
        $this->templateContext = $this;
        $this->pageConfig = $context->getPageConfig();
        parent::__construct($context, $data);
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

    /**
     * Return Gateway URL
     *
     * @return string
     */
    public function getPostUrl() {
        $gateway_url = $this->_scopeConfig->getValue(
            self::XML_PATH_GATEWAY_URL,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        return $gateway_url;
    }

    /**
     * Prepare Form Data
     *
     * @return mixed[]
     */
    public function getFormData() {
        $merchant_id = $this->_scopeConfig->getValue(
            self::XML_PATH_MERCHANT_ID,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $payment_page_lang = $this->_scopeConfig->getValue(
            self::XML_PATH_GATEWAY_LANG,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $payment_type = $this->_scopeConfig->getValue(
            self::XML_PATH_PAYMENT_TYPE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $payment_method_type = $this->_scopeConfig->getValue(
            self::XML_PATH_PAYMENT_METHOD_TYPE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $payment_methods = $this->_scopeConfig->getValue(
            self::XML_PATH_PAYMENT_METHOD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $order_no_prefix = $this->_scopeConfig->getValue(
            self::XML_PATH_ORDER_NO_PREFIX,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $secureHashSecret = $this->_scopeConfig->getValue(
            self::XML_PATH_SECURE_HASH_SECRET,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $order = $this->_getOrder();
        $currency_code = $order->getBaseCurrencyCode();

        $data = [];
        $data['orderRef'] = (($order_no_prefix && $order_no_prefix != "") ? $order_no_prefix . "-" : "") . $order->getIncrementId();
        $data['amount'] = sprintf('%.2f', $order->getBaseGrandTotal());
        $data['currCode'] = $this->_dataHelper->getIsoCurrCode($currency_code);
        $data['successUrl'] = $this->getUrl('asiapay/payment/success');
        $data['cancelUrl'] = "";
        $data['failUrl'] = "";
        $data['Lang'] = $payment_page_lang;
        $data['merchantId'] = $merchant_id;
        $data['payType'] = $payment_type;
        if($payment_method_type == "ALL" || empty($payment_methods)) {
            $data['payMethod'] = $payment_method_type;
        } else {
            $data['payMethod'] = implode(',', $payment_methods);
        }
        $data['secureHash'] = $this->_dataHelper->generatePaymentSecureHash($data['merchantId'], $data['orderRef'], $data['currCode'], $data['amount'], $data['payType'], $secureHashSecret);

        return $data;
    }
}