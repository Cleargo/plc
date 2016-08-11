<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation\Contracts;

interface FilterTemplates {
    /**
     * Returns filter template by its internal name. Returns false if no filter template with specified name is
     * defined.
     *
     * @param $type
     * @return bool|FilterTemplate
     */
    public function get($type);
}