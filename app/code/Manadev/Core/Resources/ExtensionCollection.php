<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
namespace Manadev\Core\Resources;

use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\DataObject;
use Manadev\Core\Helper\LetterCase;
use Manadev\Core\Model\Extension;
use Symfony\Component\Config\Definition\Exception\Exception;

class ExtensionCollection extends Virtual\Collection
{
    protected $_store;
    /**
     * @var \Manadev\Core\Model\Extension\Config
     */
    private $extensionConfig;

    /**
     * @param EntityFactoryInterface $entityFactory
     * @param LetterCase $caseHelper
     * @param \Manadev\Core\Auth $auth
     * @param \Manadev\Core\Model\Extension\Config $extensionConfig
     * @param \Manadev\Core\Model\ExtensionFactory $extensionFactory
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LetterCase $caseHelper,
        \Manadev\Core\Model\Feature\Config $extensionConfig
    ) {
        $this->extensionConfig = $extensionConfig;
        parent::__construct($entityFactory, $caseHelper);
    }

    public function setStore($store_id) {
        $this->_store = $store_id;
        return $this;
    }

    public function getStore() {
        return $this->_store;
    }

    protected function _addMissingOriginalItems() {
        if(is_null($this->getStore())) {
            throw new Exception(__(sprintf("You must call setStore(...) before calling load() on %s objects.", get_class($this))));
        }

        $x = 0;
        foreach ($this->extensionConfig->getExtensions($this->getStore()) as $extension) {
            $extension->setData('order', $x++);
            $extension->setData('is_extension', true);
            $this->addItem($extension);
            /** @var Extension $feature */
            foreach($extension->getData('features') as $feature) {
                $feature->setData('order', $x++);
                $feature->setData('is_extension', false);
                if(isset($this->_items[$feature->getId()])) {
                    // Append order to id so it becomes string instead of integer.
                    // The purpose is in `Manadev\Core\Blocks\Adminhtml\ExtensionControl\Feature\IsEnabledColumn` where non-numeric id's are disabled
                    $feature->setData('id', $feature->getId().'-'.$x);
                }
                $this->addItem($feature);
            }
        }
        return $this;
    }
}