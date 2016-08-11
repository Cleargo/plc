<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
namespace Manadev\Core\Resources;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Manadev\Core\Model\Extension;
use Symfony\Component\Config\Definition\Exception\Exception;

class ExtensionResource extends AbstractDb
{
    /**
     * @var \Manadev\Core\Model\Feature\Config\FileResolver
     */
    private $fileResolver;

    /**
     * Class constructor
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context|\Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Manadev\Core\Model\Feature\Config\FileResolver $fileResolver
     * @param null $connectionName
     * @internal param ComponentRegistrarInterface $moduleRegistry
     * @internal param null|string $resourcePrefix
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Manadev\Core\Model\Feature\Config\FileResolver $fileResolver,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->fileResolver = $fileResolver;
    }

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct() {
        $this->_init('mana_extension', 'id');
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return \Zend_Db_Select
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        if($field != "id") {
            $store_id = $object->getData('store_id');
            if($store_id === false) {
                throw new Exception(__(sprintf("You must call setData('store_id', ...) before calling load() on %s objects.", get_class($object))));
            }
        }
        $store_id_field = $this->getConnection()->quoteIdentifier(sprintf('%s.%s', $this->getMainTable(), 'store_id'));
        $field = $this->getConnection()->quoteIdentifier(sprintf('%s.%s', $this->getMainTable(), $field));
        $select = $this->getConnection()->select()->from($this->getMainTable())->where($field . '=?', $value);
        if(isset($store_id)) {
            $select->where($store_id_field . '=?', $store_id);
        }
        return $select;
    }

    /**
     * Perform actions before object save
     *
     * @param Extension $object
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object) {
        $this->_setInitialIsEnabledValue($object);
        $this->_setIsPending($object);
        return parent::_beforeSave($object);
    }

    public function updateModuleXml(Extension $object) {
        if($object->getId() && $object->getData('store_id') == "0") {
            $changed = false;
            $moduleXml = $this->_getModuleXmlPath($object);
            if(!$moduleXml) return;

            $parts = explode('/', $moduleXml);

            $parts[count($parts) - 1] = ($object->isEnabled()) ? 'registration.php' : 'registration.php_';
            $newModuleXmlFile = implode('/', $parts);
            if($newModuleXmlFile != $moduleXml) {
                rename($moduleXml, $newModuleXmlFile);
                $changed = true;
            }
            $object->setHasDataChanges(true);
            $object->save();
            return $changed;
        }
        return null;
    }

    protected function _getModuleXmlPath(Extension $object) {
        $files = $this->fileResolver->findInManadevModules('*registration*.php*');
        if(isset($files[$object->getData('module')])) {
            return $files[$object->getData('module')];
        }

        return false;
    }

    protected function _setIsPending($object) {
        $moduleXml = $this->_getModuleXmlPath($object);
        if (!$moduleXml) {
            $object->setData('is_pending', 0);
            return;
        }

        $parts = explode('/', $moduleXml);

        if (
            $parts[count($parts) - 1] == 'registration.php' && $object->getData('is_enabled') == "1" ||
            $parts[count($parts) - 1] != 'registration.php' && $object->getData('is_enabled') == "0"
        ) {
            $object->setData('is_pending', 0);
        } else {
            $object->setData('is_pending', 1);
        }
    }

    /**
     * @param Extension $object
     */
    protected function _setInitialIsEnabledValue($object) {
        if($object->getId() || !is_null($object->getData('is_enabled'))) {
            return;
        }

        $moduleXml = $this->_getModuleXmlPath($object);
        if (!$moduleXml) {
            $object->setData('is_enabled', 0);
            return;
        }

        $parts = explode('/', $moduleXml);
        if($parts[count($parts) - 1] == 'registration.php') {
            $object->setData('is_enabled', 1);
        } else {
            $object->setData('is_enabled', 0);
        }
    }
}