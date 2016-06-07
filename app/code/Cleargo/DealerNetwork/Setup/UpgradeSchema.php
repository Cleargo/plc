<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */

namespace Cleargo\DealerNetwork\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '0.1.1', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable('dealer_region'),
                'is_shown_frontend',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'length' => null,
                    'nullable' => false,
                    'default' => '1',
                    'comment' => 'Is Region Shown on Frontend'
                ]
            );
        }

        if (version_compare($context->getVersion(), '0.1.2', '<')) {
            /**
             * Create table 'dealer_brand'
             */
            $table = $installer->getConnection()->newTable(
                $installer->getTable('dealer_brand')
            )->addColumn(
                'brand_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Brand ID'
            )->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Brand Name'
            )->addColumn(
                'identifier',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Brand String Identifier'
            )->addColumn(
                'sort_order',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '0'],
                'Brand Sort Order'
            )->addColumn(
                'creation_time',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Brand Creation Time'
            )->addColumn(
                'update_time',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Brand Modification Time'
            )->addColumn(
                'is_active',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Is Brand Active'
            )->addIndex(
                $setup->getIdxName(
                    $installer->getTable('dealer_brand'),
                    ['name', 'identifier'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['name', 'identifier'],
                ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
            )->setComment(
                'Dealer Network Brand Table'
            );
            $installer->getConnection()->createTable($table);

            /**
             * Create table 'dealer_brand_store'
             */
            $table = $installer->getConnection()->newTable(
                $installer->getTable('dealer_brand_store')
            )->addColumn(
                'brand_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'primary' => true],
                'Brand ID'
            )->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Store ID'
            )->addIndex(
                $installer->getIdxName('dealer_brand_store', ['store_id']),
                ['store_id']
            )->addForeignKey(
                $installer->getFkName('dealer_brand_store', 'brand_id', 'dealer_brand', 'brand_id'),
                'brand_id',
                $installer->getTable('dealer_brand'),
                'brand_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName('dealer_brand_store', 'store_id', 'store', 'store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment(
                'Dealer Network Brand To Store Linkage Table'
            );
            $installer->getConnection()->createTable($table);
        }

        if (version_compare($context->getVersion(), '0.1.3', '<')) {
            /**
             * Create table 'dealer_brand_label'
             */
            $table = $installer->getConnection()->newTable(
                $installer->getTable('dealer_brand_label')
            )->addColumn(
                'brand_label_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Brand Label ID'
            )->addColumn(
                'brand_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false],
                'Brand ID'
            )->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Store ID'
            )->addColumn(
                'value',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Value'
            )->addIndex(
                $installer->getIdxName('dealer_brand_label', ['store_id']),
                ['store_id']
            )->addIndex(
                $installer->getIdxName('dealer_brand_label', ['brand_id', 'store_id']),
                ['brand_id', 'store_id']
            )->addForeignKey(
                $installer->getFkName('dealer_brand_label', 'brand_id', 'dealer_brand', 'brand_id'),
                'brand_id',
                $installer->getTable('dealer_brand'),
                'brand_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName('dealer_brand_label', 'store_id', 'store', 'store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment(
                'Dealer Network Brand Label Table'
            );
            $installer->getConnection()->createTable($table);
        }

        if (version_compare($context->getVersion(), '0.1.4', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable('dealer_dealer'),
                'brand_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => TRUE,
                    'default' => NULL,
                    'comment' => 'Brand ID'
                ]
            );
        }

        $installer->endSetup();
    }
}
