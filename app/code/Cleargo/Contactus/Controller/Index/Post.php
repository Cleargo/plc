<?php
/**
 * Created by Thomas
 * Date: 13/05/2016
 * Time: 5:02 PM
 */
namespace Cleargo\Contactus\Controller\Index;
class Post extends \Magento\Contact\Controller\Index\Post
{
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
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($post);

            $error = false;

            if (!\Zend_Validate::is(trim($post['name']), 'NotEmpty')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['telephone']), 'NotEmpty')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['enquiry']), 'NotEmpty')) {
                $error = true;
            }
            if (\Zend_Validate::is(trim($post['hideit']), 'NotEmpty')) {
                $error = true;
            }
            if ($error) {
                throw new \Exception();
            }


            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $transport = $this->_transportBuilder
                ->setTemplateIdentifier('contact_email_email_template2') // admin email
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE,
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars(['data' => $postObject])
                ->setFrom($this->scopeConfig->getValue(self::XML_PATH_EMAIL_SENDER, $storeScope))
                ->addTo($this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, $storeScope))
                ->getTransport();

            $transport->sendMessage();

            if(isset($post['email']) && $post['email'] != '' ){
                $transport2 = $this->_transportBuilder->setTemplateIdentifier('contact_from_customer')
                    ->setTemplateOptions(array('area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $this->storeManager->getStore()->getId()))
                    ->setTemplateVars(['data' => $postObject])
                    ->setFrom($this->scopeConfig->getValue(self::XML_PATH_EMAIL_SENDER, $storeScope))
                    ->addTo(trim($post['email']))
                    ->getTransport();
                $transport2->sendMessage();
            }

            $this->inlineTranslation->resume();
            $this->messageManager->addSuccess(
                __('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.')
            );
            $this->_redirect('contact/index');
            return;
    }
}