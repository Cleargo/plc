<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */
namespace Cleargo\DealerNetwork\Model\Dealer\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class Brand implements OptionSourceInterface
{
    /*
     * @var \Cleargo\DealerNetwork\Model\BrandRepository
     */
    protected $brandRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * Constructor
     * @param \Cleargo\DealerNetwork\Model\BrandRepository $brandRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        \Cleargo\DealerNetwork\Model\BrandRepository $brandRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->brandRepository = $brandRepository;
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
        $brands = $this->brandRepository->getList($searchCriteria);
        $options = [];
        foreach ($brands->getItems() as $brand) {
            $options[] = [
                'label' => $brand->getName(),
                'value' => $brand->getId(),
            ];
        }
        return $options;
    }
}
