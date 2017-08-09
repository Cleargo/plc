<?php
/**
 * Copyright ? 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\CatalogEvent\Block\Catagory;


class Event extends  \Magento\Framework\View\Element\Template
{
    protected $_modelLister;

    protected $_eventLister;

    protected $_catEvent;

    protected $_registry;

    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\CatalogEvent\Block\Catalog\Category\Event $catEvent,
        \Magento\CatalogEvent\Model\Event $modelLister,
        \Magento\CatalogEvent\Block\Event\Lister $eventLister,
        array $data = []
    ) {
        $this->_registry = $registry;
        $this->_modelLister = $modelLister;
        $this->_eventLister = $eventLister;
        $this->_catEvent = $catEvent;
        parent::__construct($context, $data);
    }

   public function getEventImageUrl($event){
        return  $this->_eventLister->getEventImageUrl($event);
    }

    public function getCurrentCategory(){
        return $this->_registry->registry("current_category");
    }
    
    public function getEvents(){
        return $this->_eventLister->getEvents();
    }

    public function getEvent(){
        return $this->_catEvent->getEvent();
    }

    public function canDisplay(){
        return $this->_catEvent->canDisplay();
    }

    public function setDisplayState($state){
        return $this->_modelLister->setDisplayState($state);
    }

    public function canDisplayCategoryPage(){
        return $this->_modelLister->canDisplayCategoryPage();
    }

    public function getEndTimeUTC($event){
        return $this->_catEvent->getEndTimeUTC($event);
    }

    public function getEventDate($type, $event){
        return $this->_catEvent->getEventDate($type, $event);
    }

    public function getEventTime($type, $event){
        return $this->_catEvent->getEventTime($type, $event);
    }

    public function getStatus(){
        return $this->_catEvent->getStatus();
    }

    public function getStatusText($event){
        return $this->_catEvent->getStatusText($event);
    }
}