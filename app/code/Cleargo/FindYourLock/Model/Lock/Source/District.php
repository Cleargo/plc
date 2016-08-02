<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Model\Lock\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class District implements OptionSourceInterface
{
    /*
     * @var \Cleargo\FindYourLock\Model\DistrictRepository
     */
    protected $districtRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * Constructor
     * @param \Cleargo\FindYourLock\Model\DistrictRepository $districtRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        \Cleargo\FindYourLock\Model\DistrictRepository $districtRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->districtRepository = $districtRepository;
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
        $countries = $this->districtRepository->getList($searchCriteria);
        $options = [];
        foreach ($countries->getItems() as $district) {
            $options[] = [
                'label' => $district->getName(),
                'value' => $district->getId(),
            ];
        }
        return $options;
    }
}
