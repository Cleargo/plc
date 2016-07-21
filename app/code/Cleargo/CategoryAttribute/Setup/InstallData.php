<?php
/* app/code/Cleargo/CategoryAttribute/Setup/InstallData.php */

namespace Cleargo\CategoryAttribute\Setup;
use Magento\Framework\Module\Setup\Migration;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Setup\CategorySetupFactory;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
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
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
        $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Category::ENTITY);
        $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
        $categorySetup->removeAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'image2'
    );
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'image2', [
                'type' => 'varchar',
                'label' => 'Image2',
                'input' => 'image',
                'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Image',
                'required' => false,
                'sort_order' => 6,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General Information',
            ]
        );
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'image3', [
                'type' => 'varchar',
                'label' => 'Image3',
                'input' => 'image',
                'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Image',
                'required' => false,
                'sort_order' => 6,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General Information',
            ]
        );
        $idg =  $categorySetup->getAttributeGroupId($entityTypeId, $attributeSetId, 'General Information');
        $categorySetup->addAttributeToGroup(
            $entityTypeId,
            $attributeSetId,
            $idg,
            'image2',
            46
        );
        $categorySetup->addAttributeToGroup(
            $entityTypeId,
            $attributeSetId,
            $idg,
            'image3',
            46
        );
        $installer->endSetup();
    }
}
