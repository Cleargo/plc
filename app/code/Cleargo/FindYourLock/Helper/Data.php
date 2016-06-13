<?php
namespace Cleargo\FindYourLock\Helper;

class Data extends \Magento\Backend\Helper\Data
{

    /**
     * get products tab Url in admin
     * @return string
     */
    public function getProductsGridUrl()
    {
        return $this->_backendUrl->getUrl('lock/lock/products', ['_current' => true]);
    }

    public function getProductUrl($productId)
    {
        return $this->_urlBuilder->getUrl('catalog/product/view', ['_current' => true,'id'=> $productId]);
    }
}