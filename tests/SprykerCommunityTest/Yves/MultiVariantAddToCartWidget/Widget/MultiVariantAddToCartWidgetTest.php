<?php

namespace SprykerCommunityTest\Yves\MultiVariantAddToCartWidget\Widget;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AttributeMapStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use SprykerCommunity\Yves\MultiVariantAddToCartWidget\Widget\MultiVariantAddToCartWidget;

class MultiVariantAddToCartWidgetTest extends Unit
{
    protected MultiVariantAddToCartWidget $multiVariantAddToCartWidget;

    protected ProductViewTransfer $productViewTransfer;

    protected AttributeMapStorageTransfer $attributeMapTransfer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->attributeMapTransfer = new AttributeMapStorageTransfer();

        $this->productViewTransfer = new ProductViewTransfer();
        $this->productViewTransfer->setAttributeMap($this->attributeMapTransfer);
    }

    public function testGetNameReturnsCorrectWidgetName(): void
    {
        // Act
        $widgetName = MultiVariantAddToCartWidget::getName();

        // Assert
        $this->assertSame('MultiVariantAddToCartWidget', $widgetName);
    }

    public function testGetTemplateReturnsCorrectTemplatePath(): void
    {
        // Act
        $templatePath = MultiVariantAddToCartWidget::getTemplate();

        // Assert
        $this->assertSame('@MultiVariantAddToCartWidget/views/multi-variant-add-to-cart-widget/multi-variant-add-to-cart-widget.twig', $templatePath);
    }

    public function testWidgetParametersAreSetCorrectly(): void
    {
        // Arrange
        $productConcreteIds = [
            'sku1' => 1,
            'sku2' => 2,
        ];

        $variantMap = [
            1 => ['color' => 'red', 'size' => 'M'],
            2 => ['color' => 'blue', 'size' => 'L'],
        ];

        $superAttributes = [
            'color' => ['red', 'blue'],
            'size' => ['M', 'L'],
        ];

        $this->attributeMapTransfer->setProductConcreteIds($productConcreteIds);
        $this->attributeMapTransfer->setAttributeVariantMap($variantMap);
        $this->attributeMapTransfer->setSuperAttributes($superAttributes);

        // Act
        $widget = new MultiVariantAddToCartWidget($this->productViewTransfer);

        // Assert
        $expectedProducts = [
            [
                'sku' => 'sku1',
                'details' => ['color' => 'red', 'size' => 'M'],
            ],
            [
                'sku' => 'sku2',
                'details' => ['color' => 'blue', 'size' => 'L'],
            ],
        ];

        $expectedAttributes = ['color', 'size'];
        $widgetParameters = $widget->getParameters();

        $this->assertSame($expectedProducts, $widgetParameters['products']);
        $this->assertSame($expectedAttributes, $widgetParameters['availableVariantAttributes']);
    }
}
