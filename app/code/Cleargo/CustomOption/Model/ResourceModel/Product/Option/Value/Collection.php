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

        /*$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();

        $current_store_image_query = "select image from catalog_product_option_type_image where store_id = $storeId";
        $current_store_image = $connection->fetchAll($current_store_image_query);

        $current_store_image = isset($current_store_image['image'])?$current_store_image['image']: '';*/

        if($storeId =='1' || $storeId == '2' || $storeId =='3'){
            foreach ($this->getData() as $data){
                $this->getItemById($data['option_type_id'])->setData('store_image',$data['default_image']);
            }
        }
        return $this;
    }



    public function getValues($storeId)
    {

        $this->addPriceToResult($storeId)->addTitleToResult($storeId);

        return $this;
    }

    /**
     * Get SQL for get record count
     *
     * @return Select
     */
    public function getSelectCountSql()
    {
        $this->_renderFilters();

        $countSelect = clone $this->getSelect();
        $countSelect->reset(\Magento\Framework\DB\Select::ORDER);
        $countSelect->reset(\Magento\Framework\DB\Select::LIMIT_COUNT);
        $countSelect->reset(\Magento\Framework\DB\Select::LIMIT_OFFSET);
        $countSelect->reset(\Magento\Framework\DB\Select::COLUMNS);

        if (!count($this->getSelect()->getPart(\Magento\Framework\DB\Select::GROUP))) {
            $countSelect->columns(new \Zend_Db_Expr('COUNT(*)'));
            return $countSelect;
        }

        $countSelect->reset(\Magento\Framework\DB\Select::GROUP);
        $group = $this->getSelect()->getPart(\Magento\Framework\DB\Select::GROUP);
        //var_dump($group);die();
        $group = array(
            "main_table.option_type_id",
            "main_table.option_type_id"
        );
        $countSelect->columns(new \Zend_Db_Expr(("COUNT(DISTINCT ".implode(", ", $group).")")));
        return $countSelect;
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
        )->group("option_type_id");
        ;

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
        )->group("option_type_id");
        ;
        return $this;
    }
}