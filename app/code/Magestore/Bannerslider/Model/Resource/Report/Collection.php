<?php
namespace Magestore\Bannerslider\Model\Resource\Report;

class Collection extends \Magento\Framework\Model\Resource\Db\Collection\AbstractCollection {
	protected function _construct() {
		$this->_init('Magestore\Bannerslider\Model\Report', 'Magestore\Bannerslider\Model\Resource\Report');
	}
}
