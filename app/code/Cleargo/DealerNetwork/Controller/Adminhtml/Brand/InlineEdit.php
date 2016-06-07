<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Controller\Adminhtml\Brand;

use Magento\Backend\App\Action\Context;
use Cleargo\DealerNetwork\Api\BrandRepositoryInterface as BrandRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Cleargo\DealerNetwork\Api\Data\BrandInterface;

class InlineEdit extends \Magento\Backend\App\Action
{
    /** @var BrandRepository  */
    protected $brandRepository;

    /** @var JsonFactory  */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param BrandRepository $brandRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        BrandRepository $brandRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->brandRepository = $brandRepository;
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
                foreach (array_keys($postItems) as $brandId) {
                    /** @var \Cleargo\DealerNetwork\Model\Brand $brand */
                    $brand = $this->brandRepository->getById($brandId);
                    try {
                        $brand->setData(array_merge($brand->getData(), $postItems[$brandId]));
                        $this->brandRepository->save($brand);
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithBrandId(
                            $brand,
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
     * Add brand name to error message
     *
     * @param BrandInterface $brand
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithBrandId(BrandInterface $brand, $errorText)
    {
        return '[Brand ID: ' . $brand->getId() . '] ' . $errorText;
    }
}
