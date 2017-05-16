<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */

namespace Cleargo\PopupForm\Setup;

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

        if (version_compare($context->getVersion(), '1.0.1', '<')){
            $setup->getConnection()->addColumn(
                $setup->getTable('customer_inquiry_option'),
                'creation_time',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    'nullable' => false,
                    'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT,
                    'comment' => 'Creation Time'
                ]
            );
            $setup->getConnection()->addColumn(
                $setup->getTable('customer_inquiry_option'),
                'update_time',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    'nullable' => false,
                    'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE,
                    'comment' => 'Modification Time'
                ]
            );
        }
        if (version_compare($context->getVersion(), '1.0.2', '<')){

            $setup->getConnection()->dropColumn(
                $setup->getTable('customer_inquiry'),
                'question_type_id'
            );

            $table = $installer->getConnection()->newTable(
                $installer->getTable('customer_inquiry_type')
            )->addColumn(
                'inquiry_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true,'primary' => true],
                'inquiry ID'
            )->addColumn(
                'question_type_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Question Type ID'
            )->addForeignKey(
                $installer->getFkName($setup->getTable('customer_inquiry_type'), 'inquiry_id', 'customer_inquiry', 'inquiry_id'),
                'inquiry_id',
                $installer->getTable('customer_inquiry'),
                'inquiry_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName($setup->getTable('customer_inquiry_type'), 'question_type_id', 'customer_inquiry_option', 'question_type_id'),
                'question_type_id',
                $installer->getTable('customer_inquiry_option'),
                'question_type_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment(
                'customer_inquiry'
            );
            $installer->getConnection()->createTable($table);
        }
        if (version_compare($context->getVersion(), '1.0.3', '<')){
            $setup->getConnection()->addColumn(
                $setup->getTable('customer_inquiry_option'),
                'bbc_email',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'default' => null,
                    'comment' => 'Email Copy To'
                ]
            );
        }
        $installer->endSetup();
    }
}
