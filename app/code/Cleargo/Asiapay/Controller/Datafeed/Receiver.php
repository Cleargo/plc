<?php
/**
 *
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\Asiapay\Controller\Datafeed;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\PageFactory;

class Receiver extends Action
{
    /**
     * Config path to payment type settings
     */
    const XML_PATH_PAYMENT_TYPE = 'payment/cleargo_asiapay/payment_action';

    /**
     * @var \Cleargo\Asiapay\Helper\Data
     */
    protected $_dataHelper;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderFactory;

    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\OrderSender
     */
    protected $orderSender;

    /**
     * @var \Cleargo\Asiapay\Model\Logger\Logger
     */
    protected $_asiapayLogger;

    public function __construct(
        Context $context,
        \Cleargo\Asiapay\Model\Logger\Logger $logger,
        \Cleargo\Asiapay\Helper\Data $dataHelper,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender
    ) {
        $this->_asiapayLogger = $logger;
        $this->_dataHelper = $dataHelper;
        $this->_orderFactory = $orderFactory;
        $this->orderSender = $orderSender;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $this->_asiapayLogger->asiapayLog("Datafeed");
        $this->_asiapayLogger->asiapayLog(json_encode($data));
        //die('OK!');
        
        //Receive POSTed variables from the gateway
        $src = $data['src'];
        $prc = $data['prc'];
        $ord = $data['Ord'];
        $holder = $data['Holder'];
        $successCode = $data['successcode'];
        $ref = $data['Ref'];
        $payRef = $data['PayRef'];
        $amt = $data['Amt'];
        $cur = $data['Cur'];
        $remark = $data['remark'];
        $authId = $data['AuthId'];
        $eci = $data['eci'];
        $payerAuth = $data['payerAuth'];
        $sourceIp = $data['sourceIp'];
        $ipCountry = $data['ipCountry'];

        if(isset($data['secureHash'])){
            $secureHash = $data['secureHash'];
        }else{
            $secureHash = "";
        }

        //confirmation sent to the gateway to explain that the variables have been sent
        echo "OK! " . "Order Ref. No.: ". $ref . " | ";

        //explode reference number and get the value only
        $flag = preg_match("/-/", $ref);

        if ($flag == 1){
            $orderId = explode("-",$ref);
            $orderNumber = $orderId[1];
        }else{
            $orderNumber = $ref;
        }

        try{
            $order = $this->_orderFactory->create()->loadByIncrementId($orderNumber);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }

        $storeId = $order->getStoreId();
        $paymentMethod = $order->getPayment()->getMethodInstance();

        //get currency type from Magento's sales order data for this order id (for comparison with the gateway's POSTed currency)
        $dbCurrency = $order->getBaseCurrencyCode();
        //convert currency type into numerical ISO code end
        $dbCurrencyIso = $this->_dataHelper->getIsoCurrCode($dbCurrency);
        //get grand total amount from Magento's sales order data for this order id (for comparison with the gateway's POSTed amount)
        $dbAmount = sprintf('%.2f', $order->getBaseGrandTotal());

        /* secureHash validation start*/
        $secureHashSecret = $paymentMethod->getConfigData('secure_hash_secret',$storeId);
        if(trim($secureHashSecret) != ""){
            $secureHashs = explode (',', $secureHash);
            while (list ($key, $value) = each ($secureHashs)) {
                $verifyResult = $this->_dataHelper->verifyPaymentDatafeed($src, $prc, $successCode, $ref, $payRef, $cur, $amt, $payerAuth, $secureHashSecret, $value);
                if ($verifyResult) {
                    break ;
                }
            }
            if (!$verifyResult) {
                exit("Secure Hash Validation Failed");
            }
        }   
        /* secureHash validation end*/

        if ($successCode == 0 && $prc == 0 && $src == 0){
            if ($dbAmount == $amt && $dbCurrencyIso == $cur){
                $payment_type = $this->_objectManager->get(
                    'Magento\Framework\App\Config\ScopeConfigInterface',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )->getValue(
                    self::XML_PATH_PAYMENT_TYPE,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $storeId
                );
                switch($payment_type) {
                    case "H":
                        $order->getPayment()->authorize(false,$order->getBaseTotalDue());
                        break;
                    case "N":
                        $order->canInvoice();
                        $order->getPayment()->capture();
                        $order->save();
                        break;
                    default:
                        exit;
                }

                // Send Order Email
                $order->setCanSendNewEmailFlag(true);
                $this->orderSender->send($order);
            } else {
                if (($dbAmount != $amt)){
                    $this->_asiapayLogger->asiapayLog("Amount value: DB " . (($dbAmount == '') ? 'NULL' : $dbAmount) . " is not equal to POSTed " . $amt . " | ");
                    $this->_asiapayLogger->asiapayLog("Possible tamper - Update failed");
                }else if (($dbCurrencyIso != $cur)){
                    $this->_asiapayLogger->asiapayLog("Currency value: DB " . (($dbCurrency == '') ? 'NULL' : $dbCurrency) . " (".$dbCurrencyIso.") is not equal to POSTed " . $cur . " | ");
                    $this->_asiapayLogger->asiapayLog("Possible tamper - Update failed");
                }else{
                    $this->_asiapayLogger->asiapayLog("Other unknown error - Update failed");
                }
            }
        } else {
            $dbState = $order->getState();
            if($dbState == \Magento\Sales\Model\Order::STATE_PROCESSING || $dbState == \Magento\Sales\Model\Order::STATE_COMPLETE){
                //do nothing here
                $this->_asiapayLogger->asiapayLog("The order state is already set to  \"".$dbState."\", so we cannot set it to \"".\Magento\Sales\Model\Order::STATE_CANCELED."\" anymore");
            }else{
                //update order status to canceled
                $comment = "Payment was Rejected. Payment Ref: " . $payRef ;
                $order->cancel()->save();
                $this->_asiapayLogger->asiapayLog("Order Status (cancelled) update successful");
                $this->_asiapayLogger->asiapayLog("Transaction Rejected / Failed.");
            }
        }
    }
}