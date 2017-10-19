<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

/**
 * Shipping data helper
 */
namespace Cleargo\Shipping\Helper;

class Data extends \Magento\Shipping\Helper\Data
{
    protected function _getTrackingUrl($key, $model, $method = 'getId')
    {
        $urlPart = "{$key}:{$model->{$method}()}:{$model->getProtectCode()}";
        $params = [
            '_direct' => 'shipping/tracking/popup',
            '_query' => ['hash' => $this->urlEncoder->encode($urlPart)]
        ];

        $currentStoreId =  $this->_storeManager->getStore()->getStoreId();
        $storeModel = $this->_storeManager->getStore($currentStoreId);
        return $storeModel->getUrl('', $params);
    }
}