<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\NewsLetterSend\Model;



class Queue extends \Magento\Newsletter\Model\Queue
{
    /**
     * Newsletter Template object
     *
     * @var \Magento\Newsletter\Model\Template
     */
    protected $_template;

    /**
     * Subscribers collection
     *
     * @var \Magento\Newsletter\Model\ResourceModel\Subscriber\Collection
     */
    protected $_subscribersCollection;

    /**
     * Save stores flag.
     *
     * @var boolean
     */
    protected $_saveStoresFlag = false;

    /**
     * Stores assigned to queue.
     *
     * @var array
     */
    protected $_stores = [];

    const STATUS_NEVER = 0;

    const STATUS_SENDING = 1;

    const STATUS_CANCEL = 2;

    const STATUS_SENT = 3;

    const STATUS_PAUSE = 4;

    /**
     * Filter for newsletter text
     *
     * @var \Magento\Newsletter\Model\Template\Filter
     */
    protected $_templateFilter;

    /**
     * Date
     *
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * Problem factory
     *
     * @var \Magento\Newsletter\Model\ProblemFactory
     */
    protected $_problemFactory;

    /**
     * Template factory
     *
     * @var \Magento\Newsletter\Model\TemplateFactory
     */
    protected $_templateFactory;

    /**
     * @var \Magento\Newsletter\Model\Queue\TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Newsletter\Model\Template\Filter $templateFilter
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Newsletter\Model\TemplateFactory $templateFactory
     * @param \Magento\Newsletter\Model\ProblemFactory $problemFactory
     * @param \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory $subscriberCollectionFactory
     * @param \Magento\Newsletter\Model\Queue\TransportBuilder $transportBuilder
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Newsletter\Model\Template\Filter $templateFilter,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Newsletter\Model\TemplateFactory $templateFactory,
        \Magento\Newsletter\Model\ProblemFactory $problemFactory,
        \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory $subscriberCollectionFactory,
        \Magento\Newsletter\Model\Queue\TransportBuilder $transportBuilder,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_templateFilter = $templateFilter;
        $this->_date = $date;
        $this->_templateFactory = $templateFactory;
        $this->_problemFactory = $problemFactory;
        $this->_subscribersCollection = $subscriberCollectionFactory->create();
        $this->_transportBuilder = $transportBuilder;
        parent::__construct(
            $context,
            $registry,
            $templateFilter,
            $date,
            $templateFactory,
            $problemFactory,
            $subscriberCollectionFactory,
            $transportBuilder,
            $resource,
            $resourceCollection,
            $data
        );
    }

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
