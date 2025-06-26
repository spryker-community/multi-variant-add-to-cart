<?php

namespace SprykerCommunity\Yves\MultiVariantAddToCartWidget\Handler;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Client\Cart\CartClientInterface;

class AddToCartHandler
{
    protected CartClientInterface $cartClient;

    public function __construct(CartClientInterface $cartClient)
    {
        $this->cartClient = $cartClient;
    }

    public function addItemsToCart(array $parameters): void
    {
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setQuote($this->cartClient->getQuote());
        foreach ($parameters as $sku => $qty) {
            $cartChangeTransfer->addItem(
                $this->createItemTransfer($sku, $qty),
            );
        }

        $this->cartClient->addValidItems($cartChangeTransfer);
    }

    protected function createItemTransfer(string $sku, int $qty): ItemTransfer
    {
        $itemTransfer = (new ItemTransfer())
            ->setSku($sku)
            ->setQuantity($qty);

        return $itemTransfer;
    }
}
