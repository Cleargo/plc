<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Controller\Adminhtml\Region;

use Magento\Backend\App\Action\Context;
use Cleargo\FindYourLock\Api\RegionRepositoryInterface as RegionRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Cleargo\FindYourLock\Api\Data\RegionInterface;

class InlineEdit extends \Magento\Backend\App\Action
{
    /** @var RegionRepository  */
    protected $regionRepository;

    /** @var JsonFactory  */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param RegionRepository $regionRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        RegionRepository $regionRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->regionRepository = $regionRepository;
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
                foreach (array_keys($postItems) as $regionId) {
                    /** @var \Cleargo\FindYourLock\Model\Region $region */
                    $region = $this->regionRepository->getById($regionId);
                    try {
                        $region->setData(array_merge($region->getData(), $postItems[$regionId]));
                        $this->regionRepository->save($region);
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithRegionId(
                            $region,
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
     * Add region name to error message
     *
     * @param RegionInterface $region
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithRegionId(RegionInterface $region, $errorText)
    {
        return '[Region ID: ' . $region->getId() . '] ' . $errorText;
    }
}
