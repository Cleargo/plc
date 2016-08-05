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
     * Sender email config path
     */
    const XML_PATH_EMAIL_SENDER = 'contact/email/sender_email_identity';
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
    protected $customerSession;
    protected $optionRepository;


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
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Cleargo\PopupForm\Model\OptionRepository $optionRepository
) {
    parent::__construct($context);
    $this->_transportBuilder = $transportBuilder;
    $this->inlineTranslation = $inlineTranslation;
    $this->scopeConfig = $scopeConfig;
    $this->resultJsonFactory = $resultJsonFactory;
    $this->inquiry = $inquiry;
    $this->customerSession = $customerSession;
    $this->storeManager = $storeManager;
    $this->optionRepository = $optionRepository;
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

        if(isset($post['email'])){
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $templateOptions = array('area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $this->storeManager->getStore()->getId());
            $from = $this->scopeConfig->getValue(self::XML_PATH_EMAIL_SENDER, $storeScope);
            $to = array($post['email'],$post['name']);
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($this->inquiry->getData());
            $postObject->setQuestion($this->optionRepository->getById($this->inquiry->getData('question_type_id'))->getData('default_label'));

            $this->inlineTranslation->suspend();
            $transport = $this->_transportBuilder->setTemplateIdentifier('inquiry_from_customer')
                ->setTemplateOptions($templateOptions)
                ->setTemplateVars(['data' => $postObject])
                ->setFrom($from)
                ->addTo($to)
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        }

        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);

    } catch (\Exception $e) {
        $this->inlineTranslation->resume();
        $response = [
            'errors' => true,
            'message' => $e->getMessage()
        ];
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);
    }
}
}