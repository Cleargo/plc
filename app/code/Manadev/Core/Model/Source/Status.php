<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\Core\Model\Source;

class Status implements \Magento\Framework\Data\OptionSourceInterface{

    public function getOptions() {
        return [
            1 => __('Enabled'),
            0 => __('Disabled'),
        ];
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: [ ['value' => '<value>', 'label' => '<label>'], ...]
     */
    public function toOptionArray() {
        $result = [];
        foreach($this->getOptions() as $value => $label) {
            $result[] = ['value' => $value, 'label' => $label];
        }
        return $result;
    }
}