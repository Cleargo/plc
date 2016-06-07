<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Model\Region\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class Country implements OptionSourceInterface
{
    /*
     * @var \Cleargo\DealerNetwork\Model\CountryRepository
     */
    protected $countryRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * Constructor
     * @param \Cleargo\DealerNetwork\Model\CountryRepository $countryRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        \Cleargo\DealerNetwork\Model\CountryRepository $countryRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->countryRepository = $countryRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->create();
        $countries = $this->countryRepository->getList($searchCriteria);
        $options = [];
        foreach ($countries->getItems() as $country) {
            $options[] = [
                'label' => $country->getName(),
                'value' => $country->getId(),
            ];
        }
        return $options;
    }
}
