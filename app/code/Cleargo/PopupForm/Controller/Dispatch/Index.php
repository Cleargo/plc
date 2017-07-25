<?php

    namespace Cleargo\PopupForm\Controller\Dispatch;

    use Magento\Framework\App\Action\Context;
    use Magento\Framework\App\Action\Action;

    class Index extends Action
    {
        protected $_helper;
        protected $_actionFlag;
        protected $redirect;
        protected $messageManager;
        protected $resultJsonFactory;
        protected $captchaStringResolver;
        /**
         * @var PageFactory

        /**
         * @param Context $context
         * @param PageFactory $resultPageFactory
         */
        public function __construct(
            Context $context,
            \Magento\Captcha\Helper\Data $helper,
            \Magento\Framework\App\ActionFlag $actionFlag,
            \Magento\Framework\Message\ManagerInterface $messageManager,
            \Magento\Framework\App\Response\RedirectInterface $redirect,
            \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
            \Magento\Captcha\Observer\CaptchaStringResolver $captchaStringResolver
        ) {
            $this->_helper = $helper;
            $this->_actionFlag = $actionFlag;
            $this->redirect = $redirect;
            $this->messageManager = $messageManager;
            $this->resultJsonFactory = $resultJsonFactory;
            $this->captchaStringResolver = $captchaStringResolver;
            parent::__construct($context);
        }

        /**
         * View Dealer List
         *
         * @return \Magento\Framework\Controller\ResultInterface
         */
        public function execute()
        {
            $captcha_arr = $this->getRequest()->getPostValue();

            $formId = 'popupform';
            $captcha = $this->_helper->getCaptcha($formId);

            if ($captcha->isRequired()) {
                /** @var \Magento\Framework\App\Action\Action $controller */
                if ($captcha->isCorrect($captcha_arr["captcha_popupform"])) {
                     echo "Correct";
                }else{
                    echo "Incorrect";
                }
            }
        }

        private function getDataPersistor()
        {
            if ($this->dataPersistor === null) {
                $this->dataPersistor = ObjectManager::getInstance()
                    ->get(DataPersistorInterface::class);
            }
            return $this->dataPersistor;
        }
    }
