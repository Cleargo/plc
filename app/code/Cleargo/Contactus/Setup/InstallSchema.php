<?php

namespace Cleargo\Contactus\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        // Get tutorial_simplenews table
        $tableName = $installer->getTable('contactus_map_location');
        // Check if the table already exists

        if ($installer->getConnection()->isTableExists($tableName) != true) {
            // Create tutorial_simplenews table
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'location_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )
                ->addColumn(
                    'title',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Title'
                )
                ->addColumn(
                    'address',
                    Table::TYPE_TEXT,
                    500,
                    ['nullable' => false, 'default' => ''],
                    'Address'
                )
                ->addColumn(
                    'xcoordinates',
                    Table::TYPE_NUMERIC,
                    [10,7],
                    ['nullable' => false, 'default' => 0],
                    'x-Coordinates'
                )
                ->addColumn(
                    'ycoordinates',
                    Table::TYPE_NUMERIC,
                    [10,7],
                    ['nullable' => false, 'default' => 0],
                    'y-Coordinates'
                )
                ->addColumn(
                    'sort_order',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false, 'default' => 0],
                    'Sort Order'
                )->addColumn(
                    'telephone',
                    Table::TYPE_TEXT,
                    30,
                    ['nullable' => false, 'default' => ''],
                    'Telephone'
                )
                ->addColumn(
                    'fax',
                    Table::TYPE_TEXT,
                    30,
                    ['nullable' => false, 'default' => ''],
                    'Fax'
                    )

                ->addColumn(
                    'email',
                    Table::TYPE_TEXT,
                    100,
                    ['nullable' => false, 'default' => ''],
                    'Email'
                )
                ->addColumn(
                    'office_hour',
                    Table::TYPE_TEXT,
                    500,
                    ['nullable' => true, 'default' => ''],
                    'Office Hour'
                )
                ->addColumn(
                    'lunch_time',
                    Table::TYPE_TEXT,
                    500,
                    ['nullable' => true, 'default' => ''],
                    'Lunch Time'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Created At'
                )
                ->addColumn(
                    'status',
                    Table::TYPE_SMALLINT,
                    null,
                    ['nullable' => false, 'default' => '0'],
                    'Status'
                )
                ->setComment('News Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);

            $table = $installer->getConnection()->newTable(
                $installer->getTable('contactus_map_location_store')
            )->addColumn(
                'location_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'primary' => true],
                'Location ID'
            )->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Store ID'
            )->setComment(
                'CMS Page To Store Linkage Table'
            );
            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}