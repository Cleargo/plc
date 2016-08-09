<?php
namespace Magestore\Bannerslider\Model\Resource;

class Slider extends \Magento\Framework\Model\Resource\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('magestore_bannerslider_slider', 'slider_id');
    }
}
