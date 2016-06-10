<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Controller\Adminhtml\Lock;

use Magento\Backend\App\Action\Context;
use Cleargo\FindYourLock\Api\LockRepositoryInterface as LockRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Cleargo\FindYourLock\Api\Data\LockInterface;

class InlineEdit extends \Magento\Backend\App\Action
{
    /** @var LockRepository  */
    protected $lockRepository;

    /** @var JsonFactory  */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param LockRepository $lockRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        LockRepository $lockRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->lockRepository = $lockRepository;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $lockId) {
                    /** @var \Cleargo\FindYourLock\Model\Lock $lock */
                    $lock = $this->lockRepository->getById($lockId);
                    try {
                        $lock->setData(array_merge($lock->getData(), $postItems[$lockId]));
                        $this->lockRepository->save($lock);
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithLockId(
                            $lock,
                            __($e->getMessage())
                        );
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add lock name to error message
     *
     * @param LockInterface $lock
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithLockId(LockInterface $lock, $errorText)
    {
        return '[Lock ID: ' . $lock->getId() . '] ' . $errorText;
    }
}
