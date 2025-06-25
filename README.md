# Multi variant product add-to-cart
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.2-8892BF.svg)](https://php.net/)



## Description

This module will add a twig-widget, which can be used with an abstract product, which has multiple variants.
The customer will then be able to bulk-add all the variants with their quantity.


## Prerequisites

1. Spryker B2B Demo Shop installed and running
2. Git access to clone the test module
3. Composer installed


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

Add the class to the "getGlobalWidgets"-function of your ShopApplicationDependencyProvider:

```php
protected function getGlobalWidgets(): array
{
    return [
        ...
        MultiVariantAddToCartWidget::class,
        ...
    ]
}
```

#### 4. Register Widget Controller Route

Add the route provider plugin to the "getRouteProvider"-function of your RouterDependencyProvider (src/Pyz/Yves/Router/RouterDependencyProvider.php)

```php
protected function getRouteProvider(): array
    {
        ...
        new MultiVariantAddToCartWidgetRouteProviderPlugin(),
        ...
    ]
}
```

### 5. Adjust yves-build

To get styles and javascript from the module, add the following to ```frontend/settings.js```

> [!IMPORTANT]
> If spyker-community are already in your project, you won't need this part.

```js

const globalSettings = {
    ...
    paths: {
        ...
        // community folders
        community: './vendor/spryker-community',
        ...
    }
    ...
}


const getAppSettingsByTheme = (namespaceConfig, theme, pathToConfig) => {
    ...
    const paths = {
        ...
        // community folders
        community: globalSettings.paths.community,
        ...
    }
    ...

    // return settings
    return {
        ...
        find: {
        // entry point patterns (components)
            componentEntryPoints: {
                // absolute dirs in which look for
                dirs: [
                    join(globalSettings.context, paths.core),
                    join(globalSettings.context, paths.eco),
                        join(globalSettings.context, paths.community), // this position is cruicial
                    join(globalSettings.context, paths.project),
                ],
                ...
            }
        ...
    }
    ...
}
```

### 6. Usage

Simply call the  ```MultiVariantAddToCartWidget``` in your template

```
{% widget 'MultiVariantAddToCartWidget' args [data.product] only %}{%endwidget%}
```
