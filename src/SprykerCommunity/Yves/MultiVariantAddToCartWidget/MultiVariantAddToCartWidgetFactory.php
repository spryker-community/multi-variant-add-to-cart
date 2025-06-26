<?php

namespace SprykerCommunity\Yves\MultiVariantAddToCartWidget;

use Spryker\Client\Cart\CartClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerCommunity\Yves\MultiVariantAddToCartWidget\Handler\AddToCartHandler;
use SprykerShop\Yves\PriceProductWidget\Dependency\Client\PriceProductWidgetToPriceProductStorageClientInterface;

class MultiVariantAddToCartWidgetFactory extends AbstractFactory
{
    public function createAddToCartHandler(): AddToCartHandler
    {
        return new AddToCartHandler(
            $this->getCartClient(),
        );
    }

    protected function getCartClient(): CartClientInterface
    {
        return $this->getProvidedDependency(MultiVariantAddToCartWidgetDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \SprykerShop\Yves\PriceProductWidget\Dependency\Client\PriceProductWidgetToPriceProductStorageClientInterface
     */
    public function getPriceProductStorageClient(): PriceProductWidgetToPriceProductStorageClientInterface
    {
        return $this->getProvidedDependency(MultiVariantAddToCartWidgetDependencyProvider::CLIENT_PRICE_PRODUCT_STORAGE);
    }
}
