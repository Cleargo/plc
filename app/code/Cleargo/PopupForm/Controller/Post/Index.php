<?php
/**
 * Created by Thomas
 * Date: 13/05/2016
 * Time: 5:02 PM
 */
namespace Cleargo\PopupForm\Controller\Post;
class  Index  extends \Magento\Framework\App\Action\Action
{
    /**
     * Recipient email config path
     */
    const XML_PATH_EMAIL_RECIPIENT = 'contact/email/recipient_email';

    /**
     * Sender email config path
     */
    const XML_PATH_EMAIL_SENDER = 'contact/email/sender_email_identity';

    /**
     * Email template config path
     */
    const XML_PATH_EMAIL_TEMPLATE = 'contact/email/email_template';

    /**
     * Enabled config path
     */
    const XML_PATH_ENABLED = 'contact/contact/enabled';

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    protected $inquiry;
    protected $resultJsonFactory;
    private $customerSession;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
    \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
    \Cleargo\PopupForm\Model\Inquiry $inquiry,
    \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
    \Magento\Customer\Model\Session $customerSession,
    \Magento\Store\Model\StoreManagerInterface $storeManager
) {
    parent::__construct($context);
    $this->_transportBuilder = $transportBuilder;
    $this->inlineTranslation = $inlineTranslation;
    $this->scopeConfig = $scopeConfig;
    $this->resultJsonFactory = $resultJsonFactory;
    $this->inquiry = $inquiry;
    $this->customerSession = $customerSession;
    $this->storeManager = $storeManager;
}

    public function execute($coreRoute = null)//contact_email_email_template2
{
    $post = $this->getRequest()->getPostValue();

    if (!$post) {
        $response = [
            'errors' => true,
            'message' => __('No Data')
        ];
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);
    }
    try {

        $error = false;

        if (!\Zend_Validate::is(trim($post['name']), 'NotEmpty')) {
            $error = true;
        }
        if (!\Zend_Validate::is(trim($post['content']), 'NotEmpty')) {
            $error = true;
        }
        if (!\Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
            $error = true;
        }
        if ($error) {
            $response = [
                'errors' => true,
                'message' => __('There is an error in the submitted input')
            ];
            $resultJson = $this->resultJsonFactory->create();
            return $resultJson->setData($response);
        }
        $this->inquiry->setData($post);
        $this->inquiry->setIsActive(1);
        if ($this->customerSession->isLoggedIn()) {
            $this->inquiry->setCustomerId($this->customerSession->getCustomerId());
        }
        $this->inquiry->save();

        $response = [
            'message' => __('Success')
        ];
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);

        if(isset($post['email'])){
            $question = [
                1 => 'Who is your favourite singer?',
                2 => 'What is your favourite pastime?',
                3 => 'What is your favourite sports team?',
                4 => 'What is the name of your primary school?',
                5 => 'What is your pet’s name?',
                6 => 'What colour do you like best?',
                7 => 'Which is your favourite festival?',
                8 => 'What is your favourite fruit?'
            ];
            $prodcut = [
                1 => 'Auxiliary Lock',
                2 => 'Cylinder',
                3 => 'Entrance Lockset',
                4 => 'Others'
            ];

            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $templateOptions = array('area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $this->storeManager->getStore()->getId());
            $from = $this->scopeConfig->getValue(self::XML_PATH_EMAIL_SENDER, $storeScope);
            $to = array($post['email'],$post['eng_first_name']);
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($this->inquiry->getData());

            $postObject->setProduct( $prodcut[$post['product_type']]);


            $this->inlineTranslation->suspend();
            $transport = $this->_transportBuilder->setTemplateIdentifier('inquiry_to_customer')
                ->setTemplateOptions($templateOptions)
                ->setTemplateVars(['data' => $postObject])
                ->setFrom($from)
                ->addTo($to)
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        }

    } catch (\Exception $e) {
        $this->inlineTranslation->resume();
        $this->messageManager->addError(
            __('We can\'t process your request right now. ')
        );
        $this->_redirect('*/*');
        return;
    }
}
}