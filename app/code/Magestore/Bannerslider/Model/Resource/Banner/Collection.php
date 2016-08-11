<?php
namespace Magestore\Bannerslider\Model\Resource\Banner;

class Collection extends \Magento\Framework\Model\Resource\Db\Collection\AbstractCollection {
	/**
	 * store view id
	 * @var int
	 */
	protected $_storeViewId = null;

	/**
	 * @var \Magento\Store\Model\StoreManagerInterface
	 */
	protected $_storeManager;

	protected $_addedTable = [];

	protected function _construct() {
		$this->_init('Magestore\Bannerslider\Model\Banner', 'Magestore\Bannerslider\Model\Resource\Banner');
	}

	/**
	 * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
	 * @param \Psr\Log\LoggerInterface $logger
	 * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
	 * @param \Magento\Framework\Event\ManagerInterface $eventManager
	 * @param \Zend_Db_Adapter_Abstract $connection
	 * @param \Magento\Framework\Model\Resource\Db\AbstractDb $resource
	 */
	public function __construct(
		\Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
		\Psr\Log\LoggerInterface $logger,
		\Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
		\Magento\Framework\Event\ManagerInterface $eventManager,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		$connection = null,
		\Magento\Framework\Model\Resource\Db\AbstractDb $resource = null
	) {
		parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection);
		$this->_storeManager = $storeManager;

		if ($storeViewId = $this->_storeManager->getStore()->getId()) {
			$this->_storeViewId = $storeViewId;
		}
	}

	/**
	 * get store view id
	 * @return int [description]
	 */
	public function getStoreViewId() {
		return $this->_storeViewId;
	}

	/**
	 * set store view id
	 * @param int $storeViewId [description]
	 */
	public function setStoreViewId($storeViewId) {
		$this->_storeViewId = $storeViewId;
		return $this;
	}

	/**
	 * Multi store view
	 * @param string|array $field
	 * @param null|string|array $condition
	 */
	public function addFieldToFilter($field, $condition = null) {
		$attributes = array(
			'name',
			'status',
			'click_url',
			'tartget',
			'image_alt',
		);
		$storeViewId = $this->getStoreViewId();
		if (in_array($field, $attributes) && $storeViewId) {
			if (!in_array($field, $this->_addedTable)) {
				$this->getSelect()
				     ->joinLeft(array($field => $this->getTable('magestore_bannerslider_value')), "main_table.banner_id = $field.banner_id" .
					     " AND $field.store_id = $storeViewId" .
					     " AND $field.attribute_code = '$field'", array()
				     );
				$this->_addedTable[] = $field;
			}
			// return parent::addFieldToFilter("IF($field.value IS NULL, main_table.$field, $field.value)", $condition);
			return parent::addFieldToFilter($field, $condition);
		}
		if ($field == 'store_id') {
			$field = 'main_table.banner_id';
		}
		return parent::addFieldToFilter($field, $condition);
	}

	/**
	 * Multi store view
	 */
	protected function _afterLoad() {
		parent::_afterLoad();
		if ($storeViewId = $this->getStoreViewId()) {
			foreach ($this->_items as $item) {
				$item->setStoreViewId($storeViewId)->getStoreViewValue();
			}
		}
		return $this;
	}
}
