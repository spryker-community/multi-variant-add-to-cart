<?php


namespace SprykerCommunity\Yves\MultiVariantAddToCartWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\PriceProductWidget\Dependency\Client\PriceProductWidgetToPriceProductStorageClientBridge;

class MultiVariantAddToCartWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_CART = 'CLIENT_CART';
    public const CLIENT_PRICE_PRODUCT_STORAGE = 'CLIENT_PRICE_PRODUCT_STORAGE';

    public function provideDependencies(Container $container): Container
    {
        $container = $this->addCartClient($container);
        $container = $this->addPriceProductStorageClient($container);

        return $container;
    }

    protected function addCartClient(Container $container): Container
    {
        $container->set(static::CLIENT_CART, function (Container $container) {
            return $container->getLocator()->cart()->client();
        });

        return $container;
    }
    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPriceProductStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRICE_PRODUCT_STORAGE, function (Container $container) {
            return new PriceProductWidgetToPriceProductStorageClientBridge(
                $container->getLocator()->priceProductStorage()->client()
            );
        });

        return $container;
    }
}
