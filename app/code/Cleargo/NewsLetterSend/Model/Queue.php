<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 */
namespace Cleargo\NewsLetterSend\Model;


/**
 * District District Model
 *
 * @method \Cleargo\FindYourLock\Model\ResourceModel\District _getResource()
 * @method \Cleargo\FindYourLock\Model\ResourceModel\District getResource()
 */
class Queue extends \Magento\Newsletter\Model\Queue
{
    public function sendPerSubscriber($count = 20)
    {
        if ($this->getQueueStatus() != self::STATUS_SENDING &&
            ($this->getQueueStatus() != self::STATUS_NEVER &&
                $this->getQueueStartAt())
        ) {
            return $this;
        }

        if (!$this->_subscribersCollection->getQueueJoinedFlag()) {
            $this->_subscribersCollection->useQueue($this);
        }

        if ($this->_subscribersCollection->getSize() == 0) {
            $this->_finishQueue();
            return $this;
        }

        $collection = $this->_subscribersCollection->useOnlyUnsent()->showCustomerInfo()->setPageSize(
            $count
        )->setCurPage(
            1
        )->load();

        $this->_transportBuilder->setTemplateData(
            [
                'template_subject' => $this->getNewsletterSubject(),
                'template_text' => $this->getNewsletterText(),
                'template_styles' => $this->getNewsletterStyles(),
                'template_filter' => $this->_templateFilter,
                'template_type' => self::TYPE_HTML,
            ]
        );

        /** @var \Magento\Newsletter\Model\Subscriber $item */
        foreach ($collection->getItems() as $item) {
            $transport = $this->_transportBuilder->setTemplateOptions(
                ['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $item->getStoreId()]
            )->setTemplateVars(
                ['subscriber' => $item]
            )->setFrom(
                ['name' => $this->getNewsletterSenderName(), 'email' => $this->getNewsletterSenderEmail()]
            )->addTo(
                $item->getSubscriberEmail(),
                $item->getSubscriberFullName()
            )->getTransport();

            try {
                $transport->sendMessage();
            } catch (\Magento\Framework\Exception\MailException $e) {
                /** @var \Magento\Newsletter\Model\Problem $problem */
                $problem = $this->_problemFactory->create();
                $problem->addSubscriberData($item);
                $problem->addQueueData($this);
                $problem->addErrorData($e);
                $problem->save();
            }
            $item->received($this);
        }

        if (count($collection->getItems()) < $count - 1 || count($collection->getItems()) == 0) {
            $this->_finishQueue();
        }
        return $this;
    }
}
