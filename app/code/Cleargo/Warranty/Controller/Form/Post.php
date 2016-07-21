<?php
/**
 * Created by Thomas
 * Date: 13/05/2016
 * Time: 5:02 PM
 */
namespace Cleargo\Warranty\Controller\Form;

use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\ScopeInterface;

class Post extends \Magento\Framework\App\Action\Action
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
    protected $warranty;

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
        \Cleargo\Warranty\Model\Warranty $warranty,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->warranty = $warranty;
        $this->storeManager = $storeManager;
    }

    public function execute($coreRoute = null)//contact_email_email_template2
    {
        $post = $this->getRequest()->getPostValue();
        /*var_dump($post);
        die();*/
        if (!$post) {
            $this->_redirect('*/*/');
            return;
        }

        $this->inlineTranslation->suspend();
        try {
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($post);

            $error = false;

           if (!\Zend_Validate::is(trim($post['eng_first_name']), 'NotEmpty')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['eng_last_name']), 'NotEmpty')) {
                $error = true;
            }
           if (!\Zend_Validate::is(trim($post['contact_one_country_code']), 'NotEmpty')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['contact_one_phone']), 'Int')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['question_type']), 'Int')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['isHKID']), 'Int')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['answer']), 'NotEmpty')) {
                $error = true;
            }
           if (!\Zend_Validate::is(trim($post['date_of_birth']), 'Date',array( 'format' => 'YYYY-MM-DD'))) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['product_type']), 'Int')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['serial_num']), 'NotEmpty')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['profile']), 'NotEmpty')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['t_combination']), 'NotEmpty')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['fp_combination']), 'NotEmpty')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['date_of_purchase']), 'Date',array( 'format' => 'YYYY-MM-DD'))) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['name_of_dealer']), 'NotEmpty')) {
                $error = true;
            }
            if ($error) {
                throw new \Exception();
            }


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
                $postObject->setQuestion( $question[$post['question_type']]);
                $postObject->setProduct( $prodcut[$post['product_type']]);


                $this->inlineTranslation->suspend();
                $transport = $this->_transportBuilder->setTemplateIdentifier('warranty_to_customer')
                    ->setTemplateOptions($templateOptions)
                    ->setTemplateVars(['data' => $postObject])
                    ->setFrom($from)
                    ->addTo($to)
                    ->getTransport();
                $transport->sendMessage();
                $this->inlineTranslation->resume();
            }

            $this->warranty->setData($post);
            $this->warranty->setIsActive(1);
            $this->warranty->save();

            $this->messageManager->addSuccess(
                __('Thank you for your MT5 registration!')
            );
            $this->_redirect('thank-you-for-mt5-registration');
            return;
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