<?php
/**
 * Copyright ? 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\CatalogEvent\Block\Product;


class Event extends  \Magento\Framework\View\Element\Template
{
    protected $_modelLister;

    protected $_eventLister;

    protected $_proEvent;

    protected $_registry;

    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\CatalogEvent\Block\Catalog\Product\Event $proEvent,
        \Magento\CatalogEvent\Model\Event $modelLister,
        \Magento\CatalogEvent\Block\Event\Lister $eventLister,
        array $data = []
    ) {
        $this->_registry = $registry;
        $this->_modelLister = $modelLister;
        $this->_eventLister = $eventLister;
        $this->_proEvent = $proEvent;
        parent::__construct($context, $data);
    }

    public function getEventImageUrl($event){
        return  $this->_eventLister->getEventImageUrl($event);
    }

    public function getEvents(){
        return $this->_eventLister->getEvents();
    }

    public function getCurrentCategory(){
        return $this->_registry->registry("current_category");
    }

    public function getEvent(){
        return $this->_proEvent->getEvent();
    }

    public function canDisplay(){
        return $this->_proEvent->canDisplay();
    }

    public function getEndTimeUTC($event){
        return $this->_proEvent->getEndTimeUTC($event);
    }

    public function getEventDate($type, $event){
        return $this->_proEvent->getEventDate($type, $event);
    }

    public function getEventTime($type, $event){
        return $this->_proEvent->getEventTime($type, $event);
    }

    public function getStatus(){
        return $this->_proEvent->getStatus();
    }

    public function getStatusText($event){
        return $this->_proEvent->getStatusText($event);
    }
}