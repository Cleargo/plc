<?php
/**
 * @File Name: Tree.php
 * @File Path: /home/zero/public_html/magento2/1.0.0-beta_v1/app/code/Magestore/Bannerslider/Block/Adminhtml/Slider/Edit/Tab/Helper/Category/Tree.php
 * @Author: zerokool - Nguyen Huu Tien
 * @Date:   2015-07-23 13:23:14
 * @Last Modified by:   zero
 * @Last Modified time: 2015-07-23 14:14:02
 */
namespace Magestore\Bannerslider\Block\Adminhtml\Slider\Edit\Tab\Helper\Category;

class Tree extends \Magento\Catalog\Block\Adminhtml\Category\Tree {
	/**
	 * @var string
	 */
	protected $_template = 'Magestore_Bannerslider::category/tree.phtml';

	/**
	 * @param \Magento\Backend\Block\Template\Context $context
	 * @param \Magento\Catalog\Model\ResourceModel\Category\Tree $categoryTree
	 * @param \Magento\Framework\Registry $registry
	 * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
	 * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
	 * @param \Magento\Framework\DB\Helper $resourceHelper
	 * @param \Magento\Backend\Model\Auth\Session $backendSession
	 * @param array $data
	 */
	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		\Magento\Catalog\Model\ResourceModel\Category\Tree $categoryTree,
		\Magento\Framework\Registry $registry,
		\Magento\Catalog\Model\CategoryFactory $categoryFactory,
		\Magento\Framework\Json\EncoderInterface $jsonEncoder,
		\Magento\Framework\DB\Helper $resourceHelper,
		\Magento\Backend\Model\Auth\Session $backendSession,
		array $data = []
	) {
		parent::__construct($context, $categoryTree, $registry, $categoryFactory, $jsonEncoder, $resourceHelper, $backendSession, $data);
	}

	public function getCategoryIds() {
		return $this->_selectedIds;
	}

	public function setCategoryIds($ids) {
		if (empty($ids)) {
			$ids = array();
		} elseif (!is_array($ids)) {
			$ids = array((int) $ids);
		}
		$this->_selectedIds = $ids;
		return $this;
	}

	/**
	 * Get JSON of a tree node or an associative array
	 *
	 * @param Varien_Data_Tree_Node|array $node
	 * @param int $level
	 * @return string
	 */
	protected function _getNodeJson($node, $level = 1) {
		$item = array();
		$item['text'] = $this->escapeHtml($node->getName());

		if ($this->_withProductCount) {
			$item['text'] .= ' (' . $node->getProductCount() . ')';
		}
		$item['id'] = $node->getId();
		$item['path'] = $node->getData('path');
		$item['cls'] = 'folder ' . ($node->getIsActive() ? 'active-category' : 'no-active-category');
		$item['allowDrop'] = false;
		$item['allowDrag'] = false;

		if ($node->hasChildren()) {
			$item['children'] = array();
			foreach ($node->getChildren() as $child) {
				$item['children'][] = $this->_getNodeJson($child, $level + 1);
			}
		}

		if (empty($item['children']) && (int) $node->getChildrenCount() > 0) {
			$item['children'] = array();
		}

		if (!empty($item['children'])) {
			$item['expanded'] = true;
		}

		if (in_array($node->getId(), $this->getCategoryIds())) {
			$item['checked'] = true;
		}

		return $item;
	}

	public function getRoot($parentNodeCategory = null, $recursionLevel = 3) {
		return $this->getRootByIds($this->getCategoryIds());
	}
}
