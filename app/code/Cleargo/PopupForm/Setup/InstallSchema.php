<?php

namespace Cleargo\PopupForm\Setup;

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

        // Check if the table already exists

        // Get tutorial_simplenews table
        $tableName = $installer->getTable('customer_inquiry');
        if ($installer->getConnection()->isTableExists($tableName) != true) {

            $table = $installer->getConnection()->newTable(
                $installer->getTable('customer_inquiry_option')
            )->addColumn(
                'question_type_id',
                Table::TYPE_INTEGER,
                10,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Question Type ID'
            )->addColumn(
                'default_label',
                Table::TYPE_TEXT,
                100,
                ['nullable' => false, 'default' => ''],
                'Name'
            )->addColumn(
                'trans_label',
                Table::TYPE_TEXT,
                2000,
                ['nullable' => true, 'default' => ''],
                'Name'
            )->addColumn(
                'is_active',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Status'
            )->setComment(
                'CMS Page To Store Linkage Table'
            );
            $installer->getConnection()->createTable($table);

            // Create tutorial_simplenews table
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'inquiry_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )->addColumn(
                    'customer_id',
                    Table::TYPE_INTEGER,
                    10,
                    [
                        'unsigned' => true,
                        'nullable' => true,
                        'default' => Null,
                    ],
                    'Customer ID'
                )
                ->addColumn(
                    'question_type_id',
                    Table::TYPE_INTEGER,
                    10,
                    [
                        'unsigned' => true,
                        'nullable' => true,
                        'default'=>null
                    ],
                    'Question Type ID'
                )
                ->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    100,
                    ['nullable' => false, 'default' => ''],
                    'Name'
                )
                ->addColumn(
                    'email',
                    Table::TYPE_TEXT,
                    150,
                    ['nullable' => false, 'default' => ''],
                    'Email'
                )
                ->addColumn(
                    'tel',
                    Table::TYPE_TEXT,
                    20,
                    ['nullable' => true, 'default' => ''],
                    'Telephone'
                )
                ->addColumn(
                    'content',
                    Table::TYPE_TEXT,
                    500,
                    ['nullable' => false, 'default' => ''],
                    'Content'
                )->addColumn(
                    'creation_time',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                    'Inquiry Creation Time'
                )->addColumn(
                    'update_time',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                    'Inquiry Modification Time'
                )->addColumn(
                    'is_active',
                    Table::TYPE_SMALLINT,
                    null,
                    ['nullable' => false, 'default' => '0'],
                    'Status'
                )->addForeignKey(
                    $installer->getFkName($tableName, 'question_type_id', 'customer_inquiry_option', 'question_type_id'),
                    'question_type_id',
                    $installer->getTable('customer_inquiry_option'),
                    'question_type_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )->addForeignKey(
                    $installer->getFkName($tableName, 'customer_id', 'customer_entity', 'entity_id'),
                    'customer_id',
                    $installer->getTable('customer_entity'),
                    'entity_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )
                ->setComment('News Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }



        $installer->endSetup();
    }
}