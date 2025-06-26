# Multi Variant Product Add-to-Cart
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.2-8892BF.svg)](https://php.net/)

## Description

This module adds a Twig widget that enhances the shopping experience for products with multiple variants. Instead of adding variants one by one, customers can select quantities for multiple variants and add them to the cart in a single action.

### Key Benefits:
- **Improved User Experience**: Customers can add multiple product variants at once
- **Time-Saving**: Reduces the number of clicks needed to add multiple variants
- **Increased Sales**: Makes it easier to purchase multiple variants, potentially increasing order values

## Features
- Bulk add-to-cart functionality for product variants
- Customizable display of variant attributes
- Seamless integration with existing Spryker product pages
- Compatible with Spryker's cart functionality

## What It Looks Like

The widget displays a table of product variants with their attributes and quantity inputs:

```
+----------------------------------------------------------+
| Multi-Variant Add to Cart                                |
+----------------------------------------------------------+
| Variant       | Color  | Size  | Quantity  |             |
|---------------|--------|-------|-----------|-------------|
| Product SKU-1 | Red    | S     | [   1   ] |             |
| Product SKU-2 | Red    | M     | [   0   ] |             |
| Product SKU-3 | Red    | L     | [   2   ] |             |
| Product SKU-4 | Blue   | S     | [   0   ] |             |
| Product SKU-5 | Blue   | M     | [   1   ] |             |
| Product SKU-6 | Blue   | L     | [   0   ] |             |
+----------------------------------------------------------+
|                                    [  Add to Cart  ]     |
+----------------------------------------------------------+
```

This allows customers to quickly select quantities for multiple variants and add them to the cart in a single action.

## Prerequisites

1. Spryker B2B Demo Shop installed and running
2. Git access to clone the module
3. Composer installed
4. Basic knowledge of Spryker's widget system


## Installation Steps

### 1. Configure Spryker Core Namespaces

Add the SprykerCommunity namespace to your Spryker configuration:

File: `config/Shared/config_default.php`

```php
<?php

// Add SprykerCommunity to the core namespaces array
$config[KernelConstants::CORE_NAMESPACES] = [
    'SprykerCommunity',  // Add this line
    'SprykerShop',
    'SprykerEco',
    'Spryker',
    'SprykerSdk',
];
```

### 2. Install the Module

Run the composer require command from your demo shop root directory:

```bash
composer require spryker-community/multi-variant-add-to-cart
```

### 3. Adjust ShopApplicationDependencyProvider

Add the widget class to the `getGlobalWidgets()` method in your ShopApplicationDependencyProvider:

```php
<?php

namespace Pyz\Yves\ShopApplication;

use SprykerCommunity\Yves\MultiVariantAddToCartWidget\Widget\MultiVariantAddToCartWidget;
use SprykerShop\Yves\ShopApplication\ShopApplicationDependencyProvider as SprykerShopApplicationDependencyProvider;

class ShopApplicationDependencyProvider extends SprykerShopApplicationDependencyProvider
{
    /**
     * @return array<string>
     */
    protected function getGlobalWidgets(): array
    {
        return [
            // Other widgets...
            MultiVariantAddToCartWidget::class,
        ];
    }
}
```

### 4. Register Widget Controller Route

Add the route provider plugin to the `getRouteProviderPlugins()` method in your RouterDependencyProvider:

```php
<?php

namespace Pyz\Yves\Router;

use SprykerCommunity\Yves\MultiVariantAddToCartWidget\Plugin\Router\MultiVariantAddToCartWidgetRouteProviderPlugin;
use Spryker\Yves\Router\RouterDependencyProvider as SprykerRouterDependencyProvider;

class RouterDependencyProvider extends SprykerRouterDependencyProvider
{
    /**
     * @return array<\Spryker\Yves\RouterExtension\Dependency\Plugin\RouteProviderPluginInterface>
     */
    protected function getRouteProviderPlugins(): array
    {
        return [
            // Other route providers...
            new MultiVariantAddToCartWidgetRouteProviderPlugin(),
        ];
    }
}
```

### 5. Adjust Yves Build Configuration

To include styles and JavaScript from the module, you need to update your frontend build configuration in `frontend/settings.js`:

> [!IMPORTANT]
> If spryker-community modules are already configured in your project, you can skip this step.

1. Add the community path to the `globalSettings.paths` object
2. Add the community path to the paths object in `getAppSettingsByTheme`
3. Add the community path to the component entry points dirs array, making sure it's positioned between eco and project paths

Here's a simplified example of what you need to add:

```bash
# Add to globalSettings.paths
community: './vendor/spryker-community'

# Add to paths in getAppSettingsByTheme
community: globalSettings.paths.community

# Add to componentEntryPoints.dirs array (position is important)
join(globalSettings.context, paths.community)  # Add between eco and project
```

4. After making these changes, rebuild your frontend assets:

```bash
npm run yves
```

### 6. Usage

To display the multi-variant add-to-cart widget on your product detail page, add the following code to your Twig template:

```twig
{% widget 'MultiVariantAddToCartWidget' args [data.product] only %}{% endwidget %}
```

The widget requires a `ProductViewTransfer` object as its argument, which is typically available as `data.product` on product detail pages.

## Verification

After installation, you can verify that the module is working correctly by:

1. Navigating to a product detail page for a product with multiple variants
2. Checking that the multi-variant add-to-cart widget is displayed
3. Selecting quantities for different variants
4. Clicking the "Add to Cart" button
5. Verifying that all selected variants are added to the cart with the correct quantities

## Troubleshooting

### Common Issues

1. **Widget not displaying**
   - Ensure the widget is properly registered in `ShopApplicationDependencyProvider`
   - Check that the product has multiple variants
   - Verify that the product view transfer contains the attribute map

2. **JavaScript or CSS not loading**
   - Make sure you've correctly configured the Yves build settings
   - Run `npm run yves` to rebuild frontend assets

3. **Route not found error when adding to cart**
   - Verify that the route provider plugin is registered in `RouterDependencyProvider`
   - Clear the router cache: `vendor/bin/console router:cache:warm-up`

4. **Items not being added to cart**
   - Check browser console for JavaScript errors
   - Verify that the cart client is working correctly with other add-to-cart functionality
