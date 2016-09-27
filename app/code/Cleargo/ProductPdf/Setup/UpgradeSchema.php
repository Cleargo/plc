<?php
/**
 * Copyright © 2016 CLEARgo. All rights reserved.
 */

namespace Cleargo\ProductPdf\Setup;

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

        if (version_compare($context->getVersion(), '1.0.1', '<')){
            $setup->getConnection()->addColumn(
                $setup->getTable('product_pdf'),
                'name',
                [
                    'type' => Table::TYPE_TEXT,
                    'nullable' => true,
                    'length' => 255,
                    'default' => null,
                    'after' => 'pdf_id',
                    'comment' => 'Frontend Display'
                ]
            );
        }

        $installer->endSetup();
    }
}
