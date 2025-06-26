<?php


namespace SprykerCommunity\Yves\MultiVariantAddToCartWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class MultiVariantAddToCartWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const ROUTE_NAME_MULTI_VARIANTS_ADD_TO_CART = 'multi-variant-add-to-cart/add';

    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addAddItemsToCartRoute($routeCollection);

        return $routeCollection;
    }

    private function addAddItemsToCartRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/multi-variant-add-to-cart/add', 'MultiVariantAddToCartWidget', 'MultiVariantAddToCartWidget', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_MULTI_VARIANTS_ADD_TO_CART, $route);

        return $routeCollection;
    }
}
