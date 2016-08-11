<?php
namespace Magestore\Bannerslider\Model\Resource;

class Report extends \Magento\Framework\Model\Resource\Db\AbstractDb {
	protected function _construct() {
		$this->_init('magestore_bannerslider_report', 'report_id');
	}
}
