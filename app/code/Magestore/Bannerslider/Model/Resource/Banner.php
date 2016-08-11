<?php
namespace Magestore\Bannerslider\Model\Resource;

class Banner extends \Magento\Framework\Model\Resource\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('magestore_bannerslider_banner', 'banner_id');
    }
}
