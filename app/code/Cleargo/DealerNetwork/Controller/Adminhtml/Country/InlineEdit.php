<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Controller\Adminhtml\Country;

use Magento\Backend\App\Action\Context;
use Cleargo\DealerNetwork\Api\CountryRepositoryInterface as CountryRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Cleargo\DealerNetwork\Api\Data\CountryInterface;

class InlineEdit extends \Magento\Backend\App\Action
{
    /** @var CountryRepository  */
    protected $countryRepository;

    /** @var JsonFactory  */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param CountryRepository $countryRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        CountryRepository $countryRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->countryRepository = $countryRepository;
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
                foreach (array_keys($postItems) as $countryId) {
                    /** @var \Cleargo\DealerNetwork\Model\Country $country */
                    $country = $this->countryRepository->getById($countryId);
                    try {
                        $country->setData(array_merge($country->getData(), $postItems[$countryId]));
                        $this->countryRepository->save($country);
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithCountryId(
                            $country,
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
     * Add country name to error message
     *
     * @param CountryInterface $country
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithCountryId(CountryInterface $country, $errorText)
    {
        return '[Country ID: ' . $country->getId() . '] ' . $errorText;
    }
}
