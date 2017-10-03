<?php


namespace Cleargo\Rma\Plugin\Magento\Rma\Helper;

class Data
{

    public function afterGetShippingCarriers(\Magento\Rma\Helper\Data $subject, $result) {
        //Your plugin code
        $result['custom'] = __('Custom Value');
        return $result;
    }
}
