<?php
namespace Magestore\Bannerslider\Model\Resource\Slider;

class Collection extends \Magento\Framework\Model\Resource\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Magestore\Bannerslider\Model\Slider', 'Magestore\Bannerslider\Model\Resource\Slider');
    }
}
