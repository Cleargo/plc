<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Controller\Adminhtml\District;

use Magento\Backend\App\Action\Context;
use Cleargo\FindYourLock\Api\DistrictRepositoryInterface as DistrictRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Cleargo\FindYourLock\Api\Data\DistrictInterface;

class InlineEdit extends \Magento\Backend\App\Action
{
    /** @var DistrictRepository  */
    protected $districtRepository;

    /** @var JsonFactory  */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param DistrictRepository $districtRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        DistrictRepository $districtRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->districtRepository = $districtRepository;
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
                foreach (array_keys($postItems) as $districtId) {
                    /** @var \Cleargo\FindYourLock\Model\District $district */
                    $district = $this->districtRepository->getById($districtId);
                    try {
                        $district->setData(array_merge($district->getData(), $postItems[$districtId]));
                        $this->districtRepository->save($district);
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithDistrictId(
                            $district,
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
     * Add district name to error message
     *
     * @param DistrictInterface $district
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithDistrictId(DistrictInterface $district, $errorText)
    {
        return '[District ID: ' . $district->getId() . '] ' . $errorText;
    }
}
