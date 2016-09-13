<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\FindYourLock\Controller\Finder;

use Cleargo\FindYourLock\Api\LockRepositoryInterface;
use Cleargo\FindYourLock\Model\Layer\Resolver;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class View extends \Magento\Framework\App\Action\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * Catalog session
     *
     * @var \Cleargo\FindYourLock\Model\Session
     */
    protected $_finderSession;

    /**
     * Catalog design
     *
     * @var \Cleargo\FindYourLock\Model\Design
     */
    protected $_finderDesign;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Cleargo\FindYourLockUrlRewrite\Model\FinderUrlPathGenerator
     */
    protected $lockUrlPathGenerator;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\Controller\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * Catalog Layer Resolver
     *
     * @var Resolver
     */
    private $layerResolver;

    /**
     * @var LockRepositoryInterface
     */
    protected $lockRepository;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Cleargo\FindYourLock\Model\Design $finderDesign
     * @param \Cleargo\FindYourLock\Model\Session $finderSession
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Cleargo\FindYourLockUrlRewrite\Model\FinderUrlPathGenerator $lockUrlPathGenerator
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
     * @param Resolver $layerResolver
     * @param LockRepositoryInterface $lockRepository
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        PageFactory $resultPageFactory,
        LockRepositoryInterface $lockRepository
    ) {

        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->_coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->lockRepository = $lockRepository;
    }

    /**
     * Initialize requested lock object
     *
     * @return \Cleargo\FindYourLock\Model\Finder
     */
    protected function _initFinder()
    {


        $urlPara = $this->getRequest()->getParam('identifier', false);
        $currentStoreId = $this->_storeManager->getStore()->getId();

        if (!$urlPara) {
            return false;
        }

        try {
            $lock = $this->lockRepository->getByIdentifier($urlPara,$currentStoreId);
        } catch (NoSuchEntityException $e) {
            return false;
        }

        $this->_coreRegistry->register('current_lock', $lock);
        try {
            $this->_eventManager->dispatch(
                'finder_controller_lock_init_after',
                ['lock' => $lock, 'controller_action' => $this]
            );
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            return false;
    }
        return $lock;
    }

    /**
     * Finder view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $this->_initFinder();
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getLayout()->initMessages();
        return $resultPage;
    }
}
