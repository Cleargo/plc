<?php
/**
 * @File Name: Categories.php
 * @File Path: /home/zero/public_html/magento2/1.0.0-beta_v1/app/code/Magestore/Bannerslider/Controller/Adminhtml/Slider/Categories.php
 * @Author: zerokool - Nguyen Huu Tien
 * @Date:   2015-07-23 13:28:48
 * @Last Modified by:   zero
 * @Last Modified time: 2015-07-23 15:36:13
 */
namespace Magestore\Bannerslider\Controller\Adminhtml\Slider;

class Categories extends \Magestore\Bannerslider\Controller\Adminhtml\Slider {

	/**
	 * @var \Magento\Framework\View\Result\PageFactory
	 */
	public function execute() {
		/** @var \Magento\Framework\Controller\Result\Raw $response */
		$response = $this->resultRawFactory->create();
		$request = $this->getRequest();
		$ids = $request->getParam('selected', array());

		if (is_array($ids)) {
			foreach ($ids as $key => &$id) {
				$id = (int) $id;
				if ($id <= 0) {
					unset($ids[$key]);
				}
			}
			$ids = array_unique($ids);
		} else {
			$ids = array();
		}
		$block = $this->_view->getLayout()->createBlock(
			'Magestore\Bannerslider\Block\Adminhtml\Slider\Edit\Tab\Helper\Category\Tree',
			'content_category',
			array('js_form_object' => $this->getRequest()->getParam('form')))
		              ->setCategoryIds($ids)
		;
		$html = $block->toHtml();
		// $data = ['html' => $html];
		$response->setHeader('Content-type', 'text/html');
		$response->setContents($html);
		return $response;
	}
}
