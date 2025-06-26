<?php

declare(strict_types=1);

namespace SprykerCommunity\Yves\MultiVariantAddToCartWidget\Widget;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use SprykerCommunity\Yves\MultiVariantAddToCartWidget\Plugin\Router\MultiVariantAddToCartWidgetRouteProviderPlugin;

/**
 * @method \SprykerCommunity\Yves\MultiVariantAddToCartWidget\MultiVariantAddToCartWidgetFactory getFactory()
 */
class MultiVariantAddToCartWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PRODUCTS_PARAMETER_NAME = 'products';

    /**
     * @var string
     */
    protected const AVAILABLE_VARIANT_ATTRIBUTES_PARAMETER_NAME = 'availableVariantAttributes';

    /**
     * @var string
     */
    protected const ADD_TO_ROUTE_ACTION = 'addToCartAction';

    /**
     * @var string
     */
    protected const IS_VISIBLE_PARAMETER_NAME = 'isVisible';

    public function __construct(ProductViewTransfer $productViewTransfer)
    {
        $this->addProducts($productViewTransfer);
        $this->addRouteAction();
    }

    public static function getName(): string
    {
        return 'MultiVariantAddToCartWidget';
    }

    public static function getTemplate(): string
    {
        return '@MultiVariantAddToCartWidget/views/multi-variant-add-to-cart-widget/multi-variant-add-to-cart-widget.twig';
    }

    protected function addProducts(ProductViewTransfer $productViewTransfer): void
    {
        $productAbstractId = $productViewTransfer->getIdProductAbstract();
        $productConcreteIds = $productViewTransfer->getAttributeMap()?->getProductConcreteIds();
        $variantMap = $productViewTransfer->getAttributeMap()?->getAttributeVariantMap();

        $variantsToOrder = [];
        $availableAttributes = [];

        if ($productConcreteIds && $variantMap) {
            $availableAttributes = array_keys($productViewTransfer->getAttributeMap()?->getSuperAttributes());

            foreach ($productConcreteIds as $sku => $concreteId) {
                $currentProductPriceTransfer = $this->getVariantPrice($concreteId, $productAbstractId, 1);
                $variantsToOrder[] = [
                    'sku' => $sku,
                    'details' => $variantMap[$concreteId],
                    'price' => $currentProductPriceTransfer->getPrice(),
                ];
            }
        }

        $this->addParameter(static::PRODUCTS_PARAMETER_NAME, $variantsToOrder);
        $this->addParameter(static::AVAILABLE_VARIANT_ATTRIBUTES_PARAMETER_NAME, $availableAttributes);
    }

    protected function addRouteAction(): void
    {
        $this->addParameter(static::ADD_TO_ROUTE_ACTION, MultiVariantAddToCartWidgetRouteProviderPlugin::ROUTE_MULTI_VARIANTS_ADD_TO_CART);
    }

    protected function getVariantPrice(int $idProductConcrete, int $idProductAbstract, int $quantity): CurrentProductPriceTransfer
    {
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setQuantity($quantity)
            ->setIdProduct($idProductConcrete)
            ->setIdProductAbstract($idProductAbstract);

        return $this->getFactory()
            ->getPriceProductStorageClient()
            ->getResolvedCurrentProductPriceTransfer($priceProductFilterTransfer);
    }
}
