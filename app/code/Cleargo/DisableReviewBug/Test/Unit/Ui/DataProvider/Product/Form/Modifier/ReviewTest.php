<?php
namespace Cleargo\DisableReviewBug\Test\Unit\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Test\Unit\Ui\DataProvider\Product\Form\Modifier\AbstractModifierTest;
use Magento\Framework\UrlInterface;
use Magento\Review\Ui\DataProvider\Product\Form\Modifier\Review;
use Magento\Framework\Module\Manager as ModuleManager;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;

/**
 * Class ReviewTest
@@ -19,36 +21,73 @@ class ReviewTest extends AbstractModifierTest
 */
class ReviewTest extends \Magento\Review\Test\Unit\Ui\DataProvider\Product\Form\Modifier\ReviewTest
{
    protected $urlBuilderMock;

    /**
     * +     * @var \PHPUnit_Framework_MockObject_MockObject
     * +     */
    private $moduleManagerMock;

    protected function setUp()
    {
        parent::setUp();
        $this->urlBuilderMock = $this->getMockBuilder(UrlInterface::class)
            ->getMockForAbstractClass();
        $this->moduleManagerMock = $this->getMock(ModuleManager::class, [], [], '', false);
    }

    /**
     * +     * @return ModifierInterface
     * +     */
    protected function createModel()
    {
        $model = $this->objectManager->getObject(Review::class, [
            'locator' => $this->locatorMock,
            'urlBuilder' => $this->urlBuilderMock,
        ]);

        $reviewClass = new \ReflectionClass(Review::class);
        $moduleManagerProperty = $reviewClass->getProperty('moduleManager');
        $moduleManagerProperty->setAccessible(true);
        $moduleManagerProperty->setValue(
            $model,
            $this->moduleManagerMock
        );

        return $model;
    }


    public function testModifyMetaDoesNotAddReviewSectionForNewProduct()
    {
        $this->productMock->expects($this->once())
            ->method('getId');

        $this->assertSame([], $this->getModel()->modifyMeta([]));
    }

    public function testModifyMetaDoesNotAddReviewSectionIfReviewModuleOutputIsDisabled()
    {
        $this->productMock->expects($this->once())
            ->method('getId')
            ->willReturn(1);
        $this->moduleManagerMock->expects($this->any())
            ->method('isOutputEnabled')
            ->with('Magento_Review')
            ->willReturn(false);

        $this->assertSame([], $this->getModel()->modifyMeta([]));
    }

    public function testModifyMetaAddsReviewSectionForExistingProductIfReviewModuleOutputIsEnabled()
    {
        $this->productMock->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $this->moduleManagerMock->expects($this->any())
            ->method('isOutputEnabled')
            ->with('Magento_Review')
            ->willReturn(true);

        $this->assertArrayHasKey(Review::GROUP_REVIEW, $this->getModel()->modifyMeta([]));
    }
}
?>