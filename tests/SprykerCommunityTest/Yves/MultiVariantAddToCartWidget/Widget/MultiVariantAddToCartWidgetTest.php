<?php

namespace SprykerCommunityTest\Yves\MultiVariantAddToCartWidget\Widget;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AttributeMapStorageTransfer;
use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use SprykerCommunity\Yves\MultiVariantAddToCartWidget\MultiVariantAddToCartWidgetDependencyProvider;
use SprykerCommunity\Yves\MultiVariantAddToCartWidget\Widget\MultiVariantAddToCartWidget;
use SprykerCommunityTest\Yves\MultiVariantAddToCartWidget\MultiVariantAddToCartWidgetTester;
use SprykerShop\Yves\PriceProductWidget\Dependency\Client\PriceProductWidgetToPriceProductStorageClientInterface;

class MultiVariantAddToCartWidgetTest extends Unit
{
    protected MultiVariantAddToCartWidget $multiVariantAddToCartWidget;

    protected ProductViewTransfer $productViewTransfer;

    protected AttributeMapStorageTransfer $attributeMapTransfer;

    protected MultiVariantAddToCartWidgetTester $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->attributeMapTransfer = new AttributeMapStorageTransfer();

        $this->productViewTransfer = new ProductViewTransfer();
        $this->productViewTransfer->setAttributeMap($this->attributeMapTransfer);
        $this->productViewTransfer->setIdProductAbstract(1);
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

        // Create mock for PriceProductStorageClient
        $priceProductStorageClientMock = $this->createMock(PriceProductWidgetToPriceProductStorageClientInterface::class);
        $priceProductStorageClientMock->method('getResolvedCurrentProductPriceTransfer')
            ->willReturn((new CurrentProductPriceTransfer())->setPrice(1000));


        $this->tester->setDependency(
            MultiVariantAddToCartWidgetDependencyProvider::CLIENT_PRICE_PRODUCT_STORAGE,
            $priceProductStorageClientMock,
        );

        // Act
        $widget = new MultiVariantAddToCartWidget($this->productViewTransfer);

        // Assert
        $widgetParameters = $widget->getParameters();
        $expectedProducts = [
            [
                'sku' => 'sku1',
                'details' => ['color' => 'red', 'size' => 'M'],
                'price' => 1000,
            ],
            [
                'sku' => 'sku2',
                'details' => ['color' => 'blue', 'size' => 'L'],
                'price' => 1000,
            ],
        ];

        $expectedAttributes = ['color', 'size'];

        $this->assertSame($expectedProducts, $widgetParameters['products']);
        $this->assertSame($expectedAttributes, $widgetParameters['availableVariantAttributes']);
    }
}
