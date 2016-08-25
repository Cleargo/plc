<?php

namespace Cleargo\CustomOption\Model\ResourceModel\Product\Option;

Class Collection extends \Magento\Catalog\Model\ResourceModel\Product\Option\Collection
{

    /**
     * Add value to result
     *
     * @param int $storeId
     * @return $this
     */
    public function addValuesToResult($storeId = null)
    {
        if ($storeId === null) {
            $storeId = $this->_storeManager->getStore()->getId();
        }
        $optionIds = [];
        foreach ($this as $option) {
            $optionIds[] = $option->getId();
        }
        if (!empty($optionIds)) {
            /** @var \Magento\Catalog\Model\ResourceModel\Product\Option\Value\Collection $values */
            $values = $this->_optionValueCollectionFactory->create();
            $values->addTitleToResult(
                $storeId
            )->addPriceToResult(
                $storeId
            )->addOptionToFilter(
                $optionIds
            )->setOrder(
                'sort_order',
                self::SORT_ORDER_ASC
            )->setOrder(
                'title',
                self::SORT_ORDER_ASC
            );

            foreach ($values as $value) {
                $optionId = $value->getOptionId();
                if ($this->getItemById($optionId)) {
                    $this->getItemById($optionId)->addValue($value);
                    $value->setOption($this->getItemById($optionId));
                }
            }
        }

        return $this;
    }

    public function getProductOptions($productId, $storeId, $requiredOnly = false)
    {

        $collection = $this->addFieldToFilter(
            'cpe.entity_id',
            $productId
        )->addTitleToResult(
            $storeId
        )->addImageToResult(
            $storeId
        )->addPriceToResult(
            $storeId
        )->setOrder(
            'sort_order',
            'asc'
        )->setOrder(
            'title',
            'asc'
        );
        if ($requiredOnly) {
            $collection->addRequiredFilter();
        }
        $collection->addValuesToResult($storeId);
        $this->getJoinProcessor()->process($collection);
        return $collection->getItems();
    }

    public function addImageToResult($storeId)
    {
        $productOptionImageTable = $this->getTable('catalog_product_option_image');
        $connection = $this->getConnection();
        $imageExpr = $connection->getCheckSql(
            'store_option_image.image IS NULL',
            'default_option_image.image',
            'store_option_image.image'
        );

        $this->getSelect()->joinLeft(
            ['default_option_image' => $productOptionImageTable],
            'default_option_image.option_id = main_table.option_id',
            ['default_image' => 'image']
        )->joinLeft(
            ['store_option_image' => $productOptionImageTable],
            'store_option_image.option_id = main_table.option_id AND ' . $connection->quoteInto(
                'store_option_image.store_id = ?',
                $storeId
            ),
            ['store_image' => 'image', 'image' => $imageExpr]
        )
           /* ->where(
            'default_option_image.store_id = ?',
            \Magento\Store\Model\Store::DEFAULT_STORE_ID
        )*/
        ;
       //var_dump($this->getSelect()->assemble());die();
        return $this;
    }

    /**
     * @return JoinProcessorInterface
     */
    private function getJoinProcessor()
    {
        if (null === $this->joinProcessor) {
            $this->joinProcessor = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface');
        }
        return $this->joinProcessor;
    }
}