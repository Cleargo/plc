<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Controller\Adminhtml\Dealer;

use Magento\Backend\App\Action\Context;
use Cleargo\DealerNetwork\Api\DealerRepositoryInterface as DealerRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Cleargo\DealerNetwork\Api\Data\DealerInterface;

class InlineEdit extends \Magento\Backend\App\Action
{
    /** @var DealerRepository  */
    protected $dealerRepository;

    /** @var JsonFactory  */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param DealerRepository $dealerRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        DealerRepository $dealerRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->dealerRepository = $dealerRepository;
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
                foreach (array_keys($postItems) as $dealerId) {
                    /** @var \Cleargo\DealerNetwork\Model\Dealer $dealer */
                    $dealer = $this->dealerRepository->getById($dealerId);
                    try {
                        $dealer->setData(array_merge($dealer->getData(), $postItems[$dealerId]));
                        $this->dealerRepository->save($dealer);
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithDealerId(
                            $dealer,
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
     * Add dealer name to error message
     *
     * @param DealerInterface $dealer
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithDealerId(DealerInterface $dealer, $errorText)
    {
        return '[Dealer ID: ' . $dealer->getId() . '] ' . $errorText;
    }
}
