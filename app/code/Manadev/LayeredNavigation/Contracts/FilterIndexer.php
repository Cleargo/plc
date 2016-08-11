<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */


namespace Manadev\LayeredNavigation\Contracts;


interface FilterIndexer {
    /**
     * Returns array of store configuration paths which are used in `index`
     * method of this data source
     * @return string[]
     */
    public function getUsedStoreConfigPaths();

    /**
     * Inserts or updates records in `mana_filter` table on global level
     * @param array $changes
     */
    public function index($changes = ['all']);
}