<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\Warranty\Block;

/**
 * Sales order history block
 */
class History extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'warranty/history.phtml';

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $_warrantyCollectionFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Sales\Model\Order\Config
     */


    /** @var \Magento\Sales\Model\ResourceModel\Order\Collection */
    protected $warranties;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $warrantyCollectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Sales\Model\Order\Config $warrantyConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Cleargo\Warranty\Model\ResourceModel\Warranty\CollectionFactory $warrantyCollectionFactory,
        \Magento\Customer\Model\Session $_customerSession,
        array $data = []
    ) {
        $this->_warrantyCollectionFactory = $warrantyCollectionFactory;
        $this->_customerSession = $_customerSession;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('My Warranties'));
    }

    /**
     * @return bool|\Magento\Sales\Model\ResourceModel\Order\Collection
     */
    public function getWarranties()
    {

        if (!($customerId = $this->_customerSession->getCustomerId())) {
            return false;
        }
        if (!$this->warranties) {
            $this->warranties = $this->_warrantyCollectionFactory->create()->addFieldToSelect(
                '*'
            )->addFieldToFilter(
                'customer_id',
                $customerId
            )->addFieldToFilter(
                'status',
                1
            )->setOrder(
                'creation_time',
                'desc'
            );
        }

        return $this->warranties;
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getWarranties()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'warranty.warranty.history.pager'
            )->setCollection(
                $this->getWarranties()
            );
            $this->setChild('pager', $pager);
            $this->getWarranties()->load();
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @param object $warranty
     * @return string
     */
    public function getViewUrl($warranty)
    {
        return $this->getUrl('warranty/warranty/view', ['warranty_id' => $warranty->getId()]);
    }

    /**
     * @param object $warranty
     * @return string
     */
    public function getTrackUrl($warranty)
    {
        return $this->getUrl('sales/order/track', ['warranty_id' => $warranty->getId()]);
    }


    /**
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('customer/account/');
    }
}
