<?php

namespace SprykerCommunity\Yves\MultiVariantAddToCartWidget\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerCommunity\Yves\MultiVariantAddToCartWidget\MultiVariantAddToCartWidgetFactory getFactory()
 */
class MultiVariantAddToCartWidgetController extends AbstractController
{
    protected const PARAM_REFERER = 'referer';

    public function indexAction(Request $request): RedirectResponse
    {
        $this->getFactory()->createAddToCartHandler()->addItemsToCart($request->request->all('multi_variant_add_to_cart'));

        return $this->redirectToReferer($request);
    }

    protected function redirectToReferer(Request $request): RedirectResponse
    {
        return $request->headers->has(static::PARAM_REFERER) ?
            $this->redirectResponseExternal($request->headers->get(static::PARAM_REFERER))
            : $this->redirectResponseInternal(CartPageRouteProviderPlugin::ROUTE_NAME_CART);
    }
}
