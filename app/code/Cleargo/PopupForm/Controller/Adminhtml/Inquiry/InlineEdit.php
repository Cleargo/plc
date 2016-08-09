<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\PopupForm\Controller\Adminhtml\Inquiry;

use Magento\Backend\App\Action\Context;
use Cleargo\PopupForm\Api\InquiryRepositoryInterface as InquiryRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Cleargo\PopupForm\Api\Data\InquiryInterface;

class InlineEdit extends \Magento\Backend\App\Action
{
    /** @var InquiryRepository  */
    protected $inquiryRepository;

    /** @var JsonFactory  */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param InquiryRepository $inquiryRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        InquiryRepository $inquiryRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->inquiryRepository = $inquiryRepository;
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
                foreach (array_keys($postItems) as $inquiryId) {
                    /** @var \Cleargo\PopupForm\Model\Inquiry $inquiry */
                    $inquiry = $this->inquiryRepository->getById($inquiryId);
                    try {
                        $inquiry->setData(array_merge($inquiry->getData(), $postItems[$inquiryId]));
                        $this->inquiryRepository->save($inquiry);
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithInquiryId(
                            $inquiry,
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
     * Add inquiry name to error message
     *
     * @param InquiryInterface $inquiry
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithInquiryId(InquiryInterface $inquiry, $errorText)
    {
        return '[Inquiry ID: ' . $inquiry->getId() . '] ' . $errorText;
    }
}
