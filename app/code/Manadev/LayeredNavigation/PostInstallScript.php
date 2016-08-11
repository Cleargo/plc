<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
namespace Manadev\LayeredNavigation;

use Manadev\LayeredNavigation\Resources\Indexers\FilterIndexer;

class PostInstallScript implements \Manadev\Core\Contracts\PostInstallScript
{
    /**
     * @var FilterIndexer
     */
    private $filterIndexer;

    public function __construct(FilterIndexer $filterIndexer) {
        $this->filterIndexer = $filterIndexer;
    }

    public function execute() {
        $this->filterIndexer->reindexAll();
    }
}