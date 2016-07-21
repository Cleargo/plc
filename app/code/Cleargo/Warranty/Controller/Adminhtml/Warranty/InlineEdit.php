<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\Warranty\Controller\Adminhtml\Warranty;

use Magento\Backend\App\Action\Context;
use Cleargo\Warranty\Api\WarrantyRepositoryInterface as WarrantyRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Cleargo\Warranty\Api\Data\WarrantyInterface;

class InlineEdit extends \Magento\Backend\App\Action
{
    /** @var WarrantyRepository  */
    protected $warrantyRepository;

    /** @var JsonFactory  */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param WarrantyRepository $warrantyRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        WarrantyRepository $warrantyRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->warrantyRepository = $warrantyRepository;
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
                foreach (array_keys($postItems) as $warrantyId) {
                    /** @var \Cleargo\Warranty\Model\Warranty $warranty */
                    $warranty = $this->warrantyRepository->getById($warrantyId);
                    try {
                        $warranty->setData(array_merge($warranty->getData(), $postItems[$warrantyId]));
                        $this->warrantyRepository->save($warranty);
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithWarrantyId(
                            $warranty,
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
     * Add warranty name to error message
     *
     * @param WarrantyInterface $warranty
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithWarrantyId(WarrantyInterface $warranty, $errorText)
    {
        return '[Warranty ID: ' . $warranty->getId() . '] ' . $errorText;
    }
}
