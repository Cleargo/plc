<?php

namespace Cleargo\CustomOption\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')){
            $table2 = $installer->getConnection()
                ->newTable(
                    $installer->getTable('catalog_product_option_type_description')
                )
                ->addColumn(
                    'option_type_description_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Option Type Description ID'
                )
                ->addColumn(
                    'option_type_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                    'Option Type ID'
                )
                ->addColumn(
                    'store_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                    'Store ID'
                )
                ->addColumn(
                    'description',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => true, 'default' => null],
                    'Description'
                )
                ->addIndex(
                    $installer->getIdxName(
                        'catalog_product_option_type_description',
                        ['option_type_id', 'store_id'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                    ),
                    ['option_type_id', 'store_id'],
                    ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
                )
                ->addIndex(
                    $installer->getIdxName('catalog_product_option_type_description', ['store_id']),
                    ['store_id']
                )
                ->addForeignKey(
                    $installer->getFkName(
                        'catalog_product_option_type_description',
                        'option_type_id',
                        'catalog_product_option_type_value',
                        'option_type_id'
                    ),
                    'option_type_id',
                    $installer->getTable('catalog_product_option_type_value'),
                    'option_type_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $installer->getFkName('catalog_product_option_type_description', 'store_id', 'store', 'store_id'),
                    'store_id',
                    $installer->getTable('store'),
                    'store_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )
                ->setComment(
                    'Catalog Product Option Type Description Table'
                );
            $installer->getConnection()
                ->createTable($table2);
        }

        $installer->endSetup();
    }
}