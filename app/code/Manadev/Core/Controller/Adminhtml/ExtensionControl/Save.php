<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
namespace Manadev\Core\Controller\Adminhtml\ExtensionControl;

use Magento\Backend\App\AbstractAction;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Manadev\Core\Model\ExtensionFactory;

class Save extends AbstractAction
{
    /**
     * @var PageFactory
     */
    private $resultPageFactory;
    /**
     * @var \Manadev\Core\Model\Feature\Config
     */
    private $extensionConfig;
    /**
     * @var \Manadev\Core\Helper
     */
    private $helper;
    /**
     * @var ExtensionFactory
     */
    private $extensionFactory;
    /**
     * @var \Magento\Framework\App\Cache\Manager
     */
    private $cacheManager;

    /**
     * @param Context $context
     * @param RedirectFactory $resultRedirectFactory
     * @param Config $extensionConfig
     * @param ExtensionFactory $extensionFactory
     * @param \Manadev\Core\Helper $helper
     * @internal param PageFactory $resultPageFactory
     * @internal param ExtensionCollection $extensionCollection
     * @internal param StoreManagerInterface $storeManager
     * @internal param Registry $registry
     */
    public function __construct(
        Context $context,
        \Manadev\Core\Model\Feature\Config $extensionConfig,
        ExtensionFactory $extensionFactory,
        \Magento\Framework\App\Cache\Manager $cacheManager,
        \Manadev\Core\Helper $helper
    ) {
        parent::__construct($context);
        $this->extensionConfig = $extensionConfig;
        $this->helper = $helper;
        $this->extensionFactory = $extensionFactory;
        $this->cacheManager = $cacheManager;
    }

    /**
     * @return Page\Interceptor
     */
    public function execute()
    {
        $extensions = $this->helper->decodeGridSerializedInput($this->getRequest()->getParam('features'));
        try {
            $useDefault = $this->getUseDefault();
            foreach($useDefault as $id) {
                $extension = $this->extensionConfig->getExtensionOrFeatureModelById($id, $this->_getStore());
                if($extension && $extension->getData('store_id') == $this->_getStore()) {
                    $extension->delete();
                }
                unset($extensions[$id]);
            }

            foreach($extensions as $id => $extension) {
                if(!is_numeric($id)) {
                    // Ignore duplicate feature.
                    unset($extensions[$id]);
                    continue;
                }
                if ($extension['is_extension'] == "false") {
                    $this->saveExtension($extension);
                    unset($extensions[$id]);
                }
            }

            foreach($extensions as $id => $extension) {
                if($extension['is_enabled']) {
                    $this->saveExtension($extension);
                    unset($extensions[$id]);
                }
            }
            foreach($extensions as $extension) {
                $this->saveExtension($extension);
            }

            $this->cacheManager->clean($this->cacheManager->getAvailableTypes());
            $this->messageManager->addSuccess(__('Your changes has been applied.'));
        } catch(\Exception $e) {
            $this->messageManager->addError(__('Something went wrong, please try again.'));
        }

        $param = [];
        if($this->_getStore() !== 0) {
            $param['store'] = $this->_getStore();
        }
        $url = '*/*/';
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath($url, $param);
    }

    protected function _getStore() {
        return $this->getRequest()->getParam('store', 0);
    }

    private function saveExtension($extension) {
        $id = $extension['id'];
        $useDefault = $this->getUseDefault();
        $extensionModel = $this->extensionConfig->getExtensionOrFeatureModelById($id, $this->_getStore());
        $extensionModel->setData('is_enabled', $extension['is_enabled']);
        if(!in_array($id, $useDefault)) {
            if($this->_getStore() != $extensionModel->getData('store_id')) {
                $testExtensionModel = $this->extensionFactory->create()
                    ->setData('store_id', $this->_getStore())
                    ->load($extension['title'], 'title');
                if($testExtensionModel->getId()) {
                    $id = $testExtensionModel->getId();
                    $extensionModel = $this->extensionConfig->getExtensionOrFeatureModelById($id, $this->_getStore());
                } else {
                    $extensionModel->unsetData('id');
                    $extensionModel->setData('store_id', $this->_getStore());
                }
            }
            $extensionModel->save();
        }

        // Disable all features if extension is disabled
        if($extension['is_extension'] == "true" && !$extension['is_enabled']) {
            foreach($extensionModel->getData('features') as $feature) {
                $is_enabled = 0;

                // If one of the extension this feature belongs to is enabled, let it stay enabled.
                if($extensions = $this->extensionConfig->getExtensionsOfFeature($feature, $this->_getStore())) {
                    foreach($extensions as $extensionModel) {
                        if($extensionModel->getData('is_enabled')) {
                            $is_enabled = 1;
                            break;
                        }
                    }
                }
                $featureModel = $this->extensionFactory->create()
                    ->setData('store_id', $this->_getStore())
                    ->load($feature['title'], 'title');
                if(!$featureModel->getId()) {
                    $featureModel->setData('title', $feature['title']);
                }

                if($feature['is_enabled'] != $is_enabled) {
                    $featureModel->setData('is_enabled', $is_enabled)
                        ->save();
                }
            }
        }
    }

    /**
     * @return mixed
     */
    protected function getUseDefault() {
        return $this->getRequest()->getParam('use_default', []);
    }
}
