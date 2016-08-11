<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface {

    /**
     * Upgrades DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        if (version_compare($context->getVersion(), '2') < 0) {
            $setup->startSetup();
            $db = $setup->getConnection();

            $tableName = 'mana_filter';
            $db->addColumn($setup->getTable($tableName), 'type', ['type' => Table::TYPE_TEXT, 'length' => 20, 'nullable' => false, 'comment' => '..']);

            $setup->endSetup();
        }
        if (version_compare($context->getVersion(), '3') < 0) {
            $setup->startSetup();
            $db = $setup->getConnection();

            $tableName = 'mana_filter_edit';
            $db->addColumn($setup->getTable($tableName), 'show_in', ['type' => Table::TYPE_TEXT, 'length' => 255, 'nullable' => true, 'comment' => '..']);

            $tableName = 'mana_filter';
            $db->addColumn($setup->getTable($tableName), 'show_in', ['type' => Table::TYPE_TEXT, 'length' => 255, 'nullable' => false, 'comment' => '..']);

            $setup->endSetup();
        }
        if (version_compare($context->getVersion(), '4') < 0) {
            $setup->startSetup();
            $db = $setup->getConnection();

            $tableName = 'mana_filter_edit';
            $db->addColumn($setup->getTable($tableName), 'minimum_product_count_per_option', ['type' => Table::TYPE_INTEGER, 'default' => '1', 'nullable' => true, 'comment' => '..']);

            $tableName = 'mana_filter';
            $db->addColumn($setup->getTable($tableName), 'minimum_product_count_per_option', ['type' => Table::TYPE_INTEGER, 'default' => '1', 'nullable' => false, 'comment' => '..']);

            $setup->endSetup();
        }
        if (version_compare($context->getVersion(), '5') < 0) {
            $setup->startSetup();
            $db = $setup->getConnection();

            $tableName = 'mana_filter_edit';
            $db->addColumn($setup->getTable($tableName), 'is_enabled_in_categories', ['type' => Table::TYPE_BOOLEAN, 'default' => '1', 'nullable' => true, 'comment' => '..']);
            $db->addColumn($setup->getTable($tableName), 'is_enabled_in_search', ['type' => Table::TYPE_BOOLEAN, 'default' => '1', 'nullable' => true, 'comment' => '..']);

            $tableName = 'mana_filter';
            $db->addColumn($setup->getTable($tableName), 'is_enabled_in_categories', ['type' => Table::TYPE_BOOLEAN, 'default' => '1', 'nullable' => false, 'comment' => '..']);
            $db->addColumn($setup->getTable($tableName), 'is_enabled_in_search', ['type' => Table::TYPE_BOOLEAN, 'default' => '1', 'nullable' => false, 'comment' => '..']);

            $setup->endSetup();
        }
        if (version_compare($context->getVersion(), '6') < 0) {
            $setup->startSetup();
            $db = $setup->getConnection();

            $tableName = 'mana_filter';
            $db->addColumn($setup->getTable($tableName), 'attribute_code', ['type' => Table::TYPE_TEXT, 'length' => 255, 'nullable' => true, 'comment' => '..']);
            $db->addColumn($setup->getTable($tableName), 'swatch_input_type', ['type' => Table::TYPE_TEXT, 'length' => 50, 'nullable' => true, 'comment' => '..']);

            $setup->endSetup();
        }

        if (version_compare($context->getVersion(), '7') < 0) {
            $setup->startSetup();
            $db = $setup->getConnection();

            $tableName = 'mana_filter_edit';
            $db->dropColumn($setup->getTable($tableName), 'data_source');

            $tableName = 'mana_filter';
            $db->dropColumn($setup->getTable($tableName), 'data_source');

            $setup->endSetup();
        }

        if (version_compare($context->getVersion(), '8') < 0) {
            $setup->startSetup();
            $db = $setup->getConnection();

            $tableName = 'mana_filter_edit';
            $db->addColumn($setup->getTable($tableName), 'type', ['type' => Table::TYPE_TEXT, 'length' => 20, 'nullable' => false, 'comment' => '..']);
            $db->addIndex($setup->getTable($tableName), $setup->getIdxName($tableName, ['type']), ['type']);

            $setup->endSetup();
        }
    }
}