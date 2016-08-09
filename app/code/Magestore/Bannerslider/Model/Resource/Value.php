<?php
namespace Magestore\Bannerslider\Model\Resource;

class Value extends \Magento\Framework\Model\Resource\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('magestore_bannerslider_value', 'value_id');
    }
}
