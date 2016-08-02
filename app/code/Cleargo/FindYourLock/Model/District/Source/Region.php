<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\FindYourLock\Model\District\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class Region implements OptionSourceInterface
{
    /*
     * @var \Cleargo\FindYourLock\Model\RegionRepository
     */
    protected $regionRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * Constructor
     * @param \Cleargo\FindYourLock\Model\RegionRepository $regionRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        \Cleargo\FindYourLock\Model\RegionRepository $regionRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->regionRepository = $regionRepository;
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
        $region = $this->regionRepository->getList($searchCriteria);
        $options = [];
        foreach ($region->getItems() as $region) {
            $options[] = [
                'label' => $region->getName(),
                'value' => $region->getId(),
            ];
        }
        return $options;
    }
}
