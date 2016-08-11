<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation\Registries\FilterTemplates;

use Manadev\Core\Exceptions\InterfaceNotImplemented;
use Manadev\LayeredNavigation\Contracts\FilterTemplate;
use Manadev\LayeredNavigation\Contracts\FilterTemplates;

abstract class BaseFilterTemplates implements FilterTemplates {
    /**
     * @var FilterTemplate[]
     */
    protected $filterTemplates;

    public function __construct(array $filterTemplates)
    {
        foreach ($filterTemplates as $filterTemplate) {
            if (!($filterTemplate instanceof FilterTemplate)) {
                throw new InterfaceNotImplemented(sprintf("'%s' does not implement '%s' interface.",
                    get_class($filterTemplate), FilterTemplate::class));
            }
        }
        $this->filterTemplates = $filterTemplates;
    }

    /**
     * Returns filter template by its internal name. Returns false if no filter template with specified name is
     * defined.
     *
     * @param $type
     * @return bool|FilterTemplate
     */
    public function get($type) {
        return isset($this->filterTemplates[$type])? $this->filterTemplates[$type] : false;
    }

}