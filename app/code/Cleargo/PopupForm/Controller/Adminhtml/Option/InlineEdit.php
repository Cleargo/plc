<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\PopupForm\Controller\Adminhtml\Option;

use Magento\Backend\App\Action\Context;
use Cleargo\PopupForm\Api\OptionRepositoryInterface as OptionRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Cleargo\PopupForm\Api\Data\OptionInterface;

class InlineEdit extends \Magento\Backend\App\Action
{
    /** @var OptionRepository  */
    protected $optionRepository;

    /** @var JsonFactory  */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param OptionRepository $optionRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        OptionRepository $optionRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->optionRepository = $optionRepository;
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
                foreach (array_keys($postItems) as $optionId) {
                    /** @var \Cleargo\PopupForm\Model\Option $option */
                    $option = $this->optionRepository->getById($optionId);
                    try {
                        $option->setData(array_merge($option->getData(), $postItems[$optionId]));
                        $this->optionRepository->save($option);
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithOptionId(
                            $option,
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
     * Add option name to error message
     *
     * @param OptionInterface $option
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithOptionId(OptionInterface $option, $errorText)
    {
        return '[Option ID: ' . $option->getId() . '] ' . $errorText;
    }
}
