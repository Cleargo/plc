<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\Core;

use Magento\Framework\App\RequestInterface;
use Manadev\Core\Contracts\PageType;
use Manadev\Core\Registries\PageTypes;

class Helper {
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;
    /**
     * @var PageTypes
     */
    protected $pageTypes;

    /**
     * @param RequestInterface $request
     * @param PageTypes $pageTypes
     */
    public function __construct(RequestInterface $request, PageTypes $pageTypes) {
        $this->request = $request;
        $this->pageTypes = $pageTypes;
    }
    public function getCurrentRoute() {
        return strtolower($this->request->getFullActionName());
    }

    /**
     * @return PageType
     */
    public function getPageType() {
        return $this->pageTypes->get($this->getCurrentRoute());
    }

    public function decodeGridSerializedInput($encoded) {
        $result = array();
        parse_str($encoded, $decoded);
        foreach ($decoded as $key => $value) {
            $result[$key] = null;
            parse_str(base64_decode($value), $result[$key]);
        }

        return $result;
    }
}