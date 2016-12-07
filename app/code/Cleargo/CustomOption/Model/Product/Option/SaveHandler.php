<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\CustomOption\Model\Product\Option;

use Magento\Catalog\Api\ProductCustomOptionRepositoryInterface as OptionRepository;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;

/**
 * Class SaveHandler
 */
class SaveHandler implements ExtensionInterface
{
    /**
     * @var OptionRepository
     */
    protected $optionRepository;

    /**
     * @param OptionRepository $optionRepository
     */
    public function __construct(
        OptionRepository $optionRepository
    ) {
        $this->optionRepository = $optionRepository;
    }

    /**
     * @param object $entity
     * @param array $arguments
     * @return \Magento\Catalog\Api\Data\ProductInterface|object
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($entity, $arguments = [])
    {
        /** @var \Magento\Catalog\Api\Data\ProductInterface $entity */
        foreach ($this->optionRepository->getProductOptions($entity) as $option) { //get all options in DB

            //$this->optionRepository->delete($option);
        }
        if ($entity->getOptions()) {
            foreach ($entity->getOptions() as $optionEdited) {

                if($optionEdited->getOptionId()){
                    $optionEdited->save();
                } else {
                    $this->optionRepository->save($optionEdited);
                }

            }
        }
        if ($entity->getDeletedOptions()) {
            foreach ($entity->getDeletedOptions() as $optionDeleted) {
                if($optionDeleted->getOptionId()){
                    $targetOpt = $this->optionRepository->get($optionDeleted->getProductSku(),$optionDeleted->getOptionId());
                    $this->optionRepository->delete($targetOpt);
                }

            }
        }

        /*if ($entity->getOptions()) {
            foreach ($entity->getOptions() as $option) {
                //$this->optionRepository->save($option);
                var_dump(get_class($option));
            }
        }*/
        return $entity;
    }
}
