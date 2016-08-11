<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */


namespace Manadev\Core\Contracts;

abstract class PageType
{
    protected $route;

    /**
     * @return mixed
     */
    public function getRoute() {
        return $this->route;
    }

    /**
     * @param mixed $route
     */
    public function setRoute($route) {
        $this->route = $route;
    }

    /**
     * @param \Manadev\LayeredNavigation\Resources\Collections\FilterCollection $filters
     */
    abstract public function limitFilterCollection($filters);
}