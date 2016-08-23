<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\Asiapay\Gateway\Command;

use Magento\Payment\Gateway\Helper\ContextHelper;
use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Sales\Model\Order;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Payment\Gateway\CommandInterface;
use Magento\Sales\Model\Order\Payment;

/**
 * Class AuthorizeCommand
 */
class AuthorizeCommand implements CommandInterface
{
    /**
     * @var \Magento\Framework\DB\TransactionFactory
     */
    protected $transactionFactory;

    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\OrderSender
     */
    protected $orderSender;

    /**
     * @var \Cleargo\Asiapay\Model\Logger\Logger
     */
    protected $_asiapayLogger;

    public function __construct(
        \Cleargo\Asiapay\Model\Logger\Logger $logger,
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender
    ) {
        $this->transactionFactory = $transactionFactory;
        $this->orderSender = $orderSender;
        $this->_asiapayLogger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(array $commandSubject)
    {
        $this->_asiapayLogger->info("test authorize start");
        $stateObject = SubjectReader::readStateObject($commandSubject);
        $paymentDO = SubjectReader::readPayment($commandSubject);

        /** @var Payment $payment */
        $payment = $paymentDO->getPayment();
        ContextHelper::assertOrderPayment($payment);
        $order = $payment->getOrder();

        if($order->canInvoice()) {
            $invoice = $order->prepareInvoice();
            $invoice->register();
            /** @var \Magento\Framework\DB\Transaction $transaction */
            $transaction = $this->transactionFactory->create();
            $transaction->addObject($invoice)
                ->addObject($invoice->getOrder())
                ->save();
            
            // Send Order Email
            $order->setCanSendNewEmailFlag(true);
            $this->orderSender->send($order);
        }

        $this->_asiapayLogger->info("test authorize end");
    }
}
