<?php


namespace Cleargo\MagentoBug\Plugin\Magento\Store\Controller\Store;

use Magento\Checkout\Model\Cart as CustomerCart;

class SwitchAction
{
    protected $cart;

    public function __construct(
        CustomerCart $cart
    ){
        $this->cart = $cart;
    }

    public function beforeExecute(
        \Magento\Store\Controller\Store\SwitchAction $subject
    ) {
        $this->cart->save();
    }
}
