<?php

namespace SprykerCommunity\Yves\MultiVariantAddToCartWidget;

use Spryker\Client\Cart\CartClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerCommunity\Yves\MultiVariantAddToCartWidget\Handler\AddToCartHandler;

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
}
