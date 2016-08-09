<?php
namespace Magestore\Bannerslider\Model\Resource\Value;

class Collection extends \Magento\Framework\Model\Resource\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Magestore\Bannerslider\Model\Value', 'Magestore\Bannerslider\Model\Resource\Value');
    }
}
