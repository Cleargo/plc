<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation\Blocks;

use Magento\Framework\View\Element\Template;
use Manadev\Core\Exceptions\NotImplemented;
use Manadev\LayeredNavigation\Engine;
use Manadev\LayeredNavigation\EngineFilter;
use Manadev\LayeredNavigation\UrlGenerator;

class Navigation extends Template {
    /**
     * @var Engine
     */
    protected $engine;
    /**
     * @var UrlGenerator
     */
    protected $urlGenerator;

    public function __construct(Template\Context $context, Engine $engine, UrlGenerator $urlGenerator,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->engine = $engine;
        $this->urlGenerator = $urlGenerator;
    }

    protected function _prepareLayout() {
        $this->engine->prepareFiltersToShowIn($this->getData('position'));

        return $this;
    }

    public function setCategoryId($category_id) {
        $this->engine->setCurrentCategory($category_id);
        $this->engine->prepareFiltersToShowIn($this->getData('position'));
    }

    public function isVisible() {
        foreach ($this->engine->getFiltersToShowIn($this->getData('position')) as $engineFilter) {
            if ($engineFilter->isVisible()) {
                return true;
            }
        }

        return false;
    }

    public function hasState() {
        foreach ($this->engine->getFilters() as $engineFilter) {
            if ($engineFilter->isApplied()) {
                return true;
            }
        }

        return false;
    }

    public function getClearUrl() {
        return $this->escapeUrl($this->urlGenerator->getClearAllUrl());
    }

    /**
     * @return EngineFilter[]
     */
    public function getFilters() {
        foreach ($this->engine->getFiltersToShowIn($this->getData('position')) as $engineFilter) {
            if ($engineFilter->isVisible()) {
                yield $engineFilter;
            }
        }
    }

    /**
     * @return EngineFilter[]
     */
    public function getAppliedFilters() {
        foreach ($this->engine->getFilters() as $engineFilter) {
            if ($engineFilter->isApplied()) {
                yield $engineFilter;
            }
        }
    }

    public function renderFilter(EngineFilter $engineFilter) {
        /* @var $filterRenderer FilterRenderer */
        $filterRenderer = $this->getChildBlock('filter_renderer');

        return $filterRenderer->render($engineFilter);
    }

    /**
     * @return int
     */
    public function getAppliedOptionCount() {
        $count = 0;
        foreach ($this->getAppliedFilters() as $engineFilter) {
            foreach ($engineFilter->getAppliedItems() as $item) {
                $count++;
            }
        }

        return $count;
    }

    public function renderAppliedItem(EngineFilter $engineFilter, $item) {
        /* @var $appliedItemRenderer AppliedItemRenderer */
        $appliedItemRenderer = $this->getChildBlock('applied_item_renderer');

        return $appliedItemRenderer->render($engineFilter, $item);
    }

}