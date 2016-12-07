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
    public function saveValues()
    {
        foreach ($this->getValues() as $value) {
            $this->setData(
                $value
            )->setData(
                'option_id',
                $this->getOption()->getId()
            )->setData(
                'store_id',
                $this->getOption()->getStoreId()
            );
            if ($this->getData('is_delete') == '1') {
                if ($this->getId()) {
                    $this->deleteValues($this->getId());
                    $this->delete();
                }
            } else {
                $this->save();
            }
        }
        //eof foreach()
        return $this;
    }
}
