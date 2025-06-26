<?php

namespace SprykerCommunityTest\Yves\MultiVariantAddToCartWidget\Handler;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Cart\CartClientInterface;
use SprykerCommunity\Yves\MultiVariantAddToCartWidget\Handler\AddToCartHandler;

class AddToCartHandlerTest extends Unit
{
    protected CartClientInterface $cartClientMock;

    protected AddToCartHandler $addToCartHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cartClientMock = $this->createMock(CartClientInterface::class);
        $this->addToCartHandler = new AddToCartHandler($this->cartClientMock);
    }

    public function testAddItemsToCartAddsItemsToCartChange(): void
    {
        // Arrange
        $quoteTransfer = new QuoteTransfer();
        $parameters = [
            'test-sku-1' => 2,
            'test-sku-2' => 3,
        ];

        $this->cartClientMock->expects($this->once())
            ->method('getQuote')
            ->willReturn($quoteTransfer);

        $this->cartClientMock->expects($this->once())
            ->method('addValidItems')
            ->with($this->callback(function (CartChangeTransfer $cartChangeTransfer) use ($quoteTransfer) {
                $this->assertSame($quoteTransfer, $cartChangeTransfer->getQuote());
                $this->assertCount(2, $cartChangeTransfer->getItems());

                $items = $cartChangeTransfer->getItems();
                $skus = array_map(function (ItemTransfer $item) {
                    return $item->getSku();
                }, iterator_to_array($items));

                $this->assertContains('test-sku-1', $skus);
                $this->assertContains('test-sku-2', $skus);

                foreach ($items as $item) {
                    $this->assertInstanceOf(ItemTransfer::class, $item);
                    if ($item->getSku() === 'test-sku-1') {
                        $this->assertSame(2, $item->getQuantity());
                    }
                    if ($item->getSku() === 'test-sku-2') {
                        $this->assertSame(3, $item->getQuantity());
                    }
                }

                return true;
            }))
            ->willReturn($quoteTransfer);

        // Act
        $this->addToCartHandler->addItemsToCart($parameters);
    }
}
