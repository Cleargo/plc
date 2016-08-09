<?php
namespace Magestore\Bannerslider\Controller\Index;

class Index extends \Magestore\Bannerslider\Controller\Index {
	/**
	 * Default customer account page
	 *
	 * @return void
	 */
	public function execute() {
		$resultPage = $this->resultPageFactory->create();
		return $resultPage;
	}
}
