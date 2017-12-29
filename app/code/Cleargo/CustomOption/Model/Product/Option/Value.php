<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile
namespace Cleargo\CustomOption\Model\Product\Option;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Option;
/**
 * Catalog product option select type model
 *
 * @method \Magento\Catalog\Model\ResourceModel\Product\Option\Value _getResource()
 * @method \Magento\Catalog\Model\ResourceModel\Product\Option\Value getResource()
 * @method int getOptionId()
 * @method \Magento\Catalog\Model\Product\Option\Value setOptionId(int $value)
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Value extends \Magento\Catalog\Model\Product\Option\Value
{

    protected $managerInterface;

    /**
     * Value constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Model\ResourceModel\Product\Option\Value\CollectionFactory $valueCollectionFactory
     * @param \Magento\Framework\ObjectManagerInterface $managerInterface
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ResourceModel\Product\Option\Value\CollectionFactory $valueCollectionFactory,
        \Magento\Framework\ObjectManagerInterface $managerInterface,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->managerInterface =$managerInterface;
        parent::__construct(
            $context,
            $registry,
            $valueCollectionFactory,
            $resource, 
            $resourceCollection,
            $data
        );
    }

    public function saveValues()
    {


        foreach ($this->getValues() as $value) {
            $valueInstance = $this->managerInterface->create('\Magento\Catalog\Model\Product\Option\Value');
            $valueInstance->setData(
                $value
            )->setData(
                'option_id',
                $this->getOption()->getId()
            )->setData(
                'store_id',
                $this->getOption()->getStoreId()
            );
            if ( isset($value['is_delete']) && $value['is_delete'] == '1') {
                if(isset($value['option_type_id'])){
                    $valueInstance->load($value['option_type_id']);
                    $valueInstance->delete();
                }
            } else {
            }
            $valueInstance->save();
            $valueInstance->clearInstance();
        }
        //eof foreach()
        return $this;
    }
}
