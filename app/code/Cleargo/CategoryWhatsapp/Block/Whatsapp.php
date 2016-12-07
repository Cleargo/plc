<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\CategoryWhatsapp\Block;

use Magento\Catalog\Block\Product\View;

/**
 * Backend form widget
 *
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
class Whatsapp extends View
{

    protected $_categoryFactory;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\CategoryFactory $_categoryFactory,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        $this->_categoryFactory = $_categoryFactory;
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );
    }
    public function test()
    {
        echo "test";
        return "123213";
    }

    public function getWhatsapp(){
        $cats = $this->getProduct()->getCategoryIds();
        $whatsapp = "";
        if(count($cats)){
            foreach ($cats as $cat){
                $_category = $this->_categoryFactory->create()->load($cat);
                if($_category->getWhatsapp()){
                    $whatsapp = $_category->getWhatsapp();
                }
            }
        }
        return $whatsapp;



    }

}
