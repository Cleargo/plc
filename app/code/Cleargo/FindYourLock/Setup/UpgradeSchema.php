<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */

namespace Cleargo\FindYourLock\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use \Magento\Framework\DB\Ddl\Table;

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

        if (version_compare($context->getVersion(), '0.1.4', '<')){
            $setup->getConnection()->addColumn(
                $setup->getTable('lock_lock'),
                'type_of_lock',
                [
                    'type' => Table::TYPE_TEXT,
                    'nullable' => true,
                    'length' => 255,
                    'default' => null,
                    'after' => 'cylinder',
                    'comment' => 'Type Of Cylinder Replacement'
                ]
            );

            $setup->getConnection()->addColumn(
                $setup->getTable('lock_lock'),
                'remarks',
                [
                    'type' => Table::TYPE_TEXT,
                    'nullable' => true,
                    'length' => 255,
                    'default' => null,
                    'after' => 'type_of_lock',
                    'comment' => 'Remarks'
                ]
            );
        }

        $installer->endSetup();
    }
}
