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
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * Constructor
     * @param \Cleargo\FindYourLock\Model\DistrictRepository $districtRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        \Cleargo\FindYourLock\Model\DistrictRepository $districtRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Store\Model\System\Store $systemStore
    ) {
        $this->districtRepository = $districtRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_systemStore = $systemStore;
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
            $store_id = implode($district->getStores());
            $options[] = [
                'label' => $district->getName() . " (" . $this->_systemStore->getStoreName($store_id) .")",
                'value' => $district->getId()
            ];
        }
        return $options;
    }
}
