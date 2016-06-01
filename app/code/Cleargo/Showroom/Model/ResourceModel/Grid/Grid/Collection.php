<?php
/**
 * Customer group collection
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\Showroom\Model\ResourceModel\Grid\Grid;

class Collection extends \Cleargo\Showroom\Model\ResourceModel\Grid\Collection
{
    /**
     * Resource initialization
     * @return $this
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        return $this;
    }
}
