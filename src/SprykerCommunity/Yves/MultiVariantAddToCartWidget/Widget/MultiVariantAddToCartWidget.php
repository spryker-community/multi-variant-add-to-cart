<?php
declare(strict_types = 1);

namespace SprykerCommunity\Yves\MultiVariantAddToCartWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

class  MultiVariantAddToCartWidget extends AbstractWidget {

    protected const string PRODUCTS_PARAMETER_NAME = 'products';

    public function __construct()
    {
        $this->addProducts();
    }

    public static function getName(): string
    {
        return 'MultiVariantAddToCartWidget';
    }

    public static function getTemplate(): string
    {
        return '@MultiVariantAddToCartWidget/views/multi-variant-add-to-cart-widget/multi-variant-add-to-cart-widget.twig';
    }

    protected function addProducts(): void
    {
        $this->addParameter(self::PRODUCTS_PARAMETER_NAME, null);
    }
}
