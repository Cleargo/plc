<?php
/* app/code/Cleargo/CategoryAttribute/Setup/UpgradeData.php */

namespace Cleargo\CategoryAttribute\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Setup\CategorySetupFactory;
/**
 * Upgrade Data script
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * Category setup factory
     *
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;

    /**
     * Init
     *
     * @param CategorySetupFactory $categorySetupFactory
     */
    public function __construct(CategorySetupFactory $categorySetupFactory)
    {
        $this->categorySetupFactory = $categorySetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if ($context->getVersion()
            && version_compare($context->getVersion(), '1.0.1') < 0
        ) {

            $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Category::ENTITY);
            $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
            $categorySetup->removeAttribute(
                \Magento\Catalog\Model\Category::ENTITY, 'category_code'
            );
            $categorySetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY, 'category_code', [
                    'type' => 'varchar',
                    'label' => 'Category Code',
                    'input' => 'text',
                    'required' => false,
                    'sort_order' => 7,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'General Information',
                ]
            );

            $idg =  $categorySetup->getAttributeGroupId($entityTypeId, $attributeSetId, 'General Information');
            $categorySetup->addAttributeToGroup(
                $entityTypeId,
                $attributeSetId,
                $idg,
                'category_code',
                46
            );
        }

        $setup->endSetup();
    }
}
