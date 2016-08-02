<?php

namespace Cleargo\Warranty\Setup;

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
        $tableName = $installer->getTable('warranty_info');
        // Check if the table already exists

        if (true) {
            // Create tutorial_simplenews table
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'warranty_id',
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
                    'increment_no',
                    Table::TYPE_TEXT,
                    100,
                    ['nullable' => false, 'default' => ''],
                    'Increment Number'
                )
                ->addColumn(
                    'salutation',
                    Table::TYPE_TEXT,
                    500,
                    ['nullable' => false, 'default' => ''],
                    'Salutation'
                )->addColumn(
                    'eng_first_name',
                    Table::TYPE_TEXT,
                    100,
                    ['nullable' => false, 'default' => ''],
                    'English First Name'
                )
                ->addColumn(
                    'eng_last_name',
                    Table::TYPE_TEXT,
                    100,
                    ['nullable' => false, 'default' => ''],
                    'English Last Name'
                )->addColumn(
                    'chi_name',
                    Table::TYPE_TEXT,
                    100,
                    ['nullable' => true, 'default' => ''],
                    'Chinese Name'
                )
                ->addColumn(
                    'company',
                    Table::TYPE_TEXT,
                    100,
                    ['nullable' => true, 'default' => ''],
                    'Company Name'
                )
                ->addColumn(
                    'hkid',
                    Table::TYPE_TEXT,
                    10,
                    ['nullable' => true, 'default' => ''],
                    'HK ID'
                )
                ->addColumn(
                    'passport_num',
                    Table::TYPE_TEXT,
                    30,
                    ['nullable' => true, 'default' => ''],
                    'Passport Number'
                )
                ->addColumn(
                    'contact_one_country_code',
                    Table::TYPE_TEXT,
                    8,
                    ['nullable' => false, 'default' => ''],
                    'Contact 1 Country Code'
                )
                ->addColumn(
                    'contact_one_phone',
                    Table::TYPE_TEXT,
                    50,
                    ['nullable' => false, 'default' => ''],
                    'Contact 1 Phone'
                )
                ->addColumn(
                    'contact_two_country_code',
                    Table::TYPE_TEXT,
                    8,
                    ['nullable' => true, 'default' => ''],
                    'Contact 2 Country Code'
                )
                ->addColumn(
                    'contact_two_phone',
                    Table::TYPE_TEXT,
                    50,
                    ['nullable' => true, 'default' => ''],
                    'Contact 2 Phone'
                )
                ->addColumn(
                    'email',
                    Table::TYPE_TEXT,
                    50,
                    ['nullable' => true, 'default' => ''],
                    'Email'
                )
                ->addColumn(
                    'question_type',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false, 'default' => 1],
                    'Security Question'
                )

                ->addColumn(
                    'answer',
                    Table::TYPE_TEXT,
                    300,
                    ['nullable' => false, 'default' => ''],
                    'Answer'
                )
                ->addColumn(
                    'date_of_birth',
                    Table::TYPE_DATE,
                    null,
                    ['nullable' => false],
                    'Date of Birth'
                )
                ->addColumn(
                    'product_type',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false, 'default' => 1],
                    'Product Type'
                )
                ->addColumn(
                    'serial_num',
                    Table::TYPE_TEXT,
                    50,
                    ['nullable' => false, 'default' => ''],
                    'Serial Number'
                )
                ->addColumn(
                    'profile',
                    Table::TYPE_TEXT,
                    50,
                    ['nullable' => false, 'default' => ''],
                    'Profile'
                )
                ->addColumn(
                    't_combination',
                    Table::TYPE_TEXT,
                    50,
                    ['nullable' => false, 'default' => ''],
                    'T combination'
                )
                ->addColumn(
                    'fp_combination',
                    Table::TYPE_TEXT,
                    50,
                    ['nullable' => false, 'default' => ''],
                    'FP combination'
                )
                ->addColumn(
                    'date_of_purchase',
                    Table::TYPE_DATE,
                    null,
                    ['nullable' => false],
                    'Date of Purchase'
                )
                ->addColumn(
                    'name_of_dealer',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Name of Dealer'
                )
                ->addColumn(
                    'invoice_no',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => true],
                    'Invoice Number'
                )
                ->addColumn(
                    'creation_time',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Created At'
                )
                ->addColumn(
                    'update_time',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Updated At'
                )
                ->addColumn(
                    'customer_id',
                    Table::TYPE_INTEGER,
                    10,
                    ['nullable' => true, 'default' => Null, 'unsigned' => true ],
                    'Customer ID'
                )
                ->addColumn(
                    'status',
                    Table::TYPE_SMALLINT,
                    null,
                    ['nullable' => false, 'default' => '0'],
                    'Status'
                )->addForeignKey(
                    $installer->getFkName('warranty_info', 'customer_id', 'customer_entity', 'entity_id'),
                    'customer_id',
                    $installer->getTable('customer_entity'),
                    'entity_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )
                ->setComment('Warranty Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);

        }

        $installer->endSetup();
    }
}