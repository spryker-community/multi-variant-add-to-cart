<?php
declare(strict_types = 1);

namespace SprykerCommunity\Yves\MultiVariantAddToCartWidget\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

class  MultiVariantAddToCartWidget extends AbstractWidget {

    protected const PRODUCTS_PARAMETER_NAME = 'products';
    protected const AVAILABLE_VARIANT_ATTRIBUTES_PARAMETER_NAME = 'availableVariantAttributes';

    public function __construct(ProductViewTransfer $productViewTransfer)
    {
        $this->addProducts($productViewTransfer);
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
        $productConcreteIds = $productViewTransfer->getAttributeMap()->getProductConcreteIds();
        $variantMap = $productViewTransfer->getAttributeMap()->getAttributeVariantMap();

        $variantsToOrder = [];
        $availableAttributes = array_keys($productViewTransfer->getAttributeMap()->getSuperAttributes());

        foreach ($productConcreteIds as $sku => $concreteId) {
            $variantsToOrder[] = [
                'sku' => $sku,
                'details' => $variantMap[$concreteId]
            ];
        }

        $this->addParameter(self::PRODUCTS_PARAMETER_NAME, $variantsToOrder);
        $this->addParameter(self::AVAILABLE_VARIANT_ATTRIBUTES_PARAMETER_NAME, $availableAttributes);
    }
}
