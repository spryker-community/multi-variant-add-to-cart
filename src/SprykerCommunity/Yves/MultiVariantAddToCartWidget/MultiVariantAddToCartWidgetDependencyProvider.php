<?php

namespace SprykerCommunity\Yves\MultiVariantAddToCartWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class MultiVariantAddToCartWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CART = 'CLIENT_CART';

    public function provideDependencies(Container $container): Container
    {
        $container = $this->addCartClient($container);

        return $container;
    }

    protected function addCartClient(Container $container): Container
    {
        $container->set(static::CLIENT_CART, function (Container $container) {
            return $container->getLocator()->cart()->client();
        });

        return $container;
    }
}
