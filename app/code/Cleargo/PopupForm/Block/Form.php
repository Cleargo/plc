<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 18/5/2016
 * Time: 11:55 AM
 */
namespace Cleargo\PopupForm\Block;
use Magento\Framework\View\Element\Template;

class Form extends \Magento\Framework\View\Element\Template
{
    protected $optionFactory;

    public function __construct(
        Template\Context $context,
        \Cleargo\PopupForm\Model\ResourceModel\Option\CollectionFactory $optionFactory,
        array $data = []
    ){
        parent::__construct($context, $data);
        $this->_isScopePrivate = true;
        $this->optionFactory = $optionFactory;
    }

    public function getCheckBox(){
        return $this->optionFactory->create()->toCheckboxArray($this->_storeManager->getStore()->getCode());
    }
    
    public function getAjaxUrl(){
        return $this->getUrl('popupForm/post');
    }
    
    public function getDispatchUrl(){
        return $this->getUrl('popupForm/dispatch');
    }
}