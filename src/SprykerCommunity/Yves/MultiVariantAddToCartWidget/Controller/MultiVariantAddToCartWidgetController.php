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
    /**
     * @var string
     */
    protected const PARAMETER_MULTI_VARIANT_ADD_TO_CART = 'multi_variant_add_to_cart';

    /**
     * @var string
     */
    protected const PARAM_REFERER = 'referer';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ITEMS_ADDED_TO_CART_SUCCESS ='customer.account.shopping_list.items.added_to_cart';

    public function indexAction(Request $request): RedirectResponse
    {
        $quoteTransfer = $this->getFactory()->createAddToCartHandler()->addItemsToCart($request->request->all(self::PARAMETER_MULTI_VARIANT_ADD_TO_CART));

        if($quoteTransfer->getErrors()->count() === 0) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_ITEMS_ADDED_TO_CART_SUCCESS);
            return $this->redirectToReferer($request);
        }

        foreach ($quoteTransfer->getErrors() as $error) {
            $this->addErrorMessage($error->getMessage());
        }

        return $this->redirectToReferer($request);
    }

    protected function redirectToReferer(Request $request): RedirectResponse
    {
        return $request->headers->has(static::PARAM_REFERER) ?
            $this->redirectResponseExternal($request->headers->get(static::PARAM_REFERER))
            : $this->redirectResponseInternal(CartPageRouteProviderPlugin::ROUTE_NAME_CART);
    }
}
