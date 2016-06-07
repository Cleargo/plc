<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */

namespace Cleargo\DealerNetwork\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'dealer_country'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('dealer_country')
        )->addColumn(
            'country_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Country ID'
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Country Name'
        )->addColumn(
            'identifier',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Country String Identifier'
        )->addColumn(
            'sort_order',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '0'],
            'Country Sort Order'
        )->addColumn(
            'creation_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Country Creation Time'
        )->addColumn(
            'update_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
            'Country Modification Time'
        )->addColumn(
            'is_active',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Is Country Active'
        )->addIndex(
            $setup->getIdxName(
                $installer->getTable('dealer_country'),
                ['name', 'identifier'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            ['name', 'identifier'],
            ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->setComment(
            'Dealer Network Country Table'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'dealer_country_store'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('dealer_country_store')
        )->addColumn(
            'country_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'primary' => true],
            'Country ID'
        )->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Store ID'
        )->addIndex(
            $installer->getIdxName('dealer_country_store', ['store_id']),
            ['store_id']
        )->addForeignKey(
            $installer->getFkName('dealer_country_store', 'country_id', 'dealer_country', 'country_id'),
            'country_id',
            $installer->getTable('dealer_country'),
            'country_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('dealer_country_store', 'store_id', 'store', 'store_id'),
            'store_id',
            $installer->getTable('store'),
            'store_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Dealer Network Country To Store Linkage Table'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'dealer_region'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('dealer_region')
        )->addColumn(
            'region_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Region ID'
        )->addColumn(
            'country_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false],
            'Country ID'
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Region Name'
        )->addColumn(
            'identifier',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Region String Identifier'
        )->addColumn(
            'sort_order',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '0'],
            'Region Sort Order'
        )->addColumn(
            'creation_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Region Creation Time'
        )->addColumn(
            'update_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
            'Region Modification Time'
        )->addColumn(
            'is_active',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Is Region Active'
        )->addIndex(
            $setup->getIdxName(
                $installer->getTable('dealer_region'),
                ['name', 'identifier'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            ['name', 'identifier'],
            ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->addForeignKey(
            $installer->getFkName('dealer_region', 'country_id', 'dealer_country', 'country_id'),
            'country_id',
            $installer->getTable('dealer_country'),
            'country_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Dealer Network Region Table'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'dealer_region_store'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('dealer_region_store')
        )->addColumn(
            'region_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'primary' => true],
            'Region ID'
        )->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Store ID'
        )->addIndex(
            $installer->getIdxName('dealer_region_store', ['store_id']),
            ['store_id']
        )->addForeignKey(
            $installer->getFkName('dealer_region_store', 'region_id', 'dealer_region', 'region_id'),
            'region_id',
            $installer->getTable('dealer_region'),
            'region_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('dealer_region_store', 'store_id', 'store', 'store_id'),
            'store_id',
            $installer->getTable('store'),
            'store_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Dealer Network Region To Store Linkage Table'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'dealer_dealer'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('dealer_dealer')
        )->addColumn(
            'dealer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Dealer ID'
        )->addColumn(
            'region_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false],
            'Region ID'
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Dealer Name'
        )->addColumn(
            'address',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            [],
            'Dealer Address'
        )->addColumn(
            'tel',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Dealer Telephone'
        )->addColumn(
            'identifier',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Dealer String Identifier'
        )->addColumn(
            'sort_order',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '0'],
            'Dealer Sort Order'
        )->addColumn(
            'creation_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Dealer Creation Time'
        )->addColumn(
            'update_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
            'Dealer Modification Time'
        )->addColumn(
            'is_active',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Is Dealer Active'
        )->addIndex(
            $setup->getIdxName(
                $installer->getTable('dealer_dealer'),
                ['name', 'identifier'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            ['name', 'identifier'],
            ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->addForeignKey(
            $installer->getFkName('dealer_dealer', 'region_id', 'dealer_region', 'region_id'),
            'region_id',
            $installer->getTable('dealer_region'),
            'region_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Dealer Network Dealer Table'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'dealer_dealer_store'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('dealer_dealer_store')
        )->addColumn(
            'dealer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'primary' => true],
            'Dealer ID'
        )->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Store ID'
        )->addIndex(
            $installer->getIdxName('dealer_dealer_store', ['store_id']),
            ['store_id']
        )->addForeignKey(
            $installer->getFkName('dealer_dealer_store', 'dealer_id', 'dealer_dealer', 'dealer_id'),
            'dealer_id',
            $installer->getTable('dealer_dealer'),
            'dealer_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('dealer_dealer_store', 'store_id', 'store', 'store_id'),
            'store_id',
            $installer->getTable('store'),
            'store_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Dealer Network Dealer To Store Linkage Table'
        );
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
