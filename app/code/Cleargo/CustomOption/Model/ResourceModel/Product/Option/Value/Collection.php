<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 23/8/2016
 * Time: 6:36 PM
 */

namespace Cleargo\CustomOption\Model\ResourceModel\Product\Option\Value;

Class Collection extends \Magento\Catalog\Model\ResourceModel\Product\Option\Value\Collection
{
    protected function _construct()
    {
        $this->_init(
            'Magento\Catalog\Model\Product\Option\Value',
            'Cleargo\CustomOption\Model\ResourceModel\Product\Option\Value'
        );

    }

    /**
     * Add price to result
     *
     * @param int $storeId
     * @return $this
     */
    public function addPriceToResult($storeId)
    {
        $optionTypeTable = $this->getTable('catalog_product_option_type_price');
        $priceExpr = $this->getConnection()->getCheckSql(
            'store_value_price.price IS NULL',
            'default_value_price.price',
            'store_value_price.price'
        );
        $priceTypeExpr = $this->getConnection()->getCheckSql(
            'store_value_price.price_type IS NULL',
            'default_value_price.price_type',
            'store_value_price.price_type'
        );

        $joinExprDefault = 'default_value_price.option_type_id = main_table.option_type_id AND ' .
            $this->getConnection()->quoteInto(
                'default_value_price.store_id = ?',
                \Magento\Store\Model\Store::DEFAULT_STORE_ID
            );
        $joinExprStore = 'store_value_price.option_type_id = main_table.option_type_id AND ' .
            $this->getConnection()->quoteInto('store_value_price.store_id = ?', $storeId);
        $this->getSelect()->joinLeft(
            ['default_value_price' => $optionTypeTable],
            $joinExprDefault,
            ['default_price' => 'price', 'default_price_type' => 'price_type']
        )->joinLeft(
            ['store_value_price' => $optionTypeTable],
            $joinExprStore,
            [
                'store_price' => 'price',
                'store_price_type' => 'price_type',
                'price' => $priceExpr,
                'price_type' => $priceTypeExpr
            ]
        );

        $this->addImageToResult($storeId);
        $this->addDescriptionToResult($storeId);

        return $this;
    }

    /**
     * Add price, image to result
     *
     * @param int $storeId
     * @return $this
     */

    public function getValues($storeId)
    {

        $this->addPriceToResult($storeId)->addTitleToResult($storeId)->addImageToResult($storeId);

        return $this;
    }

    /**
     * Add image result
     *
     * @param int $storeId
     * @return $this
     */
    public function addImageToResult($storeId)
    {
        $optionImageTable = $this->getTable('catalog_product_option_type_image');
        $imageExpr = $this->getConnection()->getCheckSql(
            'store_value_image.image IS NULL',
            'default_value_image.image',
            'store_value_image.image'
        );

        $joinExpr = 'store_value_image.option_type_id = main_table.option_type_id AND ' .
            $this->getConnection()->quoteInto('store_value_image.store_id = ?', $storeId);
        $this->getSelect()->joinLeft(
            ['default_value_image' => $optionImageTable],
            'default_value_image.option_type_id = main_table.option_type_id',
            ['default_image' => 'image']
        )->joinLeft(
            ['store_value_image' => $optionImageTable],
            $joinExpr,
            ['store_image' => 'image', 'image' => $imageExpr]
        )
        ;
        //var_dump($this->getSelect()->assemble());die();
        return $this;
    }
    /**
     * Add image result
     *
     * @param int $storeId
     * @return $this
     */
    public function addDescriptionToResult($storeId)
    {
        $optionDescriptionTable = $this->getTable('catalog_product_option_type_description');
        $descriptionExpr = $this->getConnection()->getCheckSql(
            'store_value_description.description IS NULL',
            'default_value_description.description',
            'store_value_description.description'
        );

        $joinExpr = 'store_value_description.option_type_id = main_table.option_type_id AND ' .
            $this->getConnection()->quoteInto('store_value_description.store_id = ?', $storeId);
        $this->getSelect()->joinLeft(
            ['default_value_description' => $optionDescriptionTable],
            'default_value_description.option_type_id = main_table.option_type_id',
            ['default_description' => 'description']
        )->joinLeft(
            ['store_value_description' => $optionDescriptionTable],
            $joinExpr,
            ['store_description' => 'description', 'description' => $descriptionExpr]
        )
        ;
        //var_dump($this->getSelect()->assemble());die();
        return $this;
    }
}