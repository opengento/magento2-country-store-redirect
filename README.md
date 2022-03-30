# Country Store Redirect Module for Magento 2

[![Latest Stable Version](https://img.shields.io/packagist/v/opengento/module-country-store-redirect.svg?style=flat-square)](https://packagist.org/packages/opengento/module-country-store-redirect)
[![License: MIT](https://img.shields.io/github/license/opengento/magento2-country-store-redirect.svg?style=flat-square)](./LICENSE) 
[![Packagist](https://img.shields.io/packagist/dt/opengento/module-country-store-redirect.svg?style=flat-square)](https://packagist.org/packages/opengento/module-country-store-redirect/stats)
[![Packagist](https://img.shields.io/packagist/dm/opengento/module-country-store-redirect.svg?style=flat-square)](https://packagist.org/packages/opengento/module-country-store-redirect/stats)

This module will redirect the customers regarding their country of origin, on their first visit session.

 - [Setup](#setup)
   - [Composer installation](#composer-installation)
   - [Setup the module](#setup-the-module)
 - [Features](#features)
 - [Settings](#settings)
 - [Documentation](#documentation)
 - [Support](#support)
 - [Authors](#authors)
 - [License](#license)

## Setup

Magento 2 Open Source or Commerce edition is required.

### Composer installation

Run the following composer command:

```
composer require opengento/module-country-store-redirect
```

### Setup the module

Run the following magento command:

```
bin/magento setup:upgrade
```

**If you are in production mode, do not forget to recompile and redeploy the static resources.**

### Varnish Usage

Update the varnish vcl with the following instruction:

```
# Bypass first visit and redirect
if (req.http.cookie !~ "PHPSESSID=") {
    return (pass);
}
```

## Features

### First Session Redirect

Redirect the visitor on its first session visit, depending on its country. This feature can be disabled by store view
scope, so the first redirect is only enabled on your main domain for example.

This feature can have non desirable behavior, for example for robots crawling your website. That's why you can set a
list of user agents to ignore and to not process the redirection in this case.
Moreover, a list of controller actions to ignore is also configurable.

Now you can select which visitor's country resolver to use, the available resolvers are:
 - Default Session Country (the country already set in session, or the default for the current store view).
 - CloudFare GEO-IP Country (use visitor'scountry available in the HTTP header value). CloudFare Country GEO-IP must be
 enabled, [see more](https://support.cloudflare.com/hc/en-us/articles/200168236-Configuring-Cloudflare-IP-Geolocation).

Any third party developers can add its own country resolver. Please read the developer section for further details.

### Country to store mapping

Define the country to store relation. This configuration will allows Magento to redirect the visitor to the correct
store view depending of the visitor's country. Multiple countries can be assigned to a single store view.

## Settings

The configuration for this module is available in `Stores > Configuration > General > Country Redirect`.  

## Documentation

### How to add a country resolver

Create a new final class and implement the following interface: `Opengento\CountryStoreRedirect\Model\Country\ResolverInterface`.
The method `public function getCountryCode(): string` should returns the visitor's country code of origin. The country
should be compliant to ISO 3166-1 alpha-2 format.

Register the new country resolver in the method factory, `Vendor/Module/etc/di.xml`:

```xml
<type name="Opengento\CountryStore\Model\Resolver\ResolverFactory">
    <arguments>
        <argument name="resolvers" xsi:type="array">
            <item name="customCountryResolver" xsi:type="string">Vendor\Module\Model\Country\Resolver\CustomCountryResolver</item>
        </argument>
    </arguments>
</type>
```

If you want the resolver ba available in settings, register it in the resolver list `Vendor/Module/etc/di.xml`:

```xml
<virtualType name="Opengento\CountryStore\Model\Config\Source\CountryResolver">
    <arguments>
        <argument name="options" xsi:type="array">
            <item name="customCountryResolver" xsi:type="array">
                <item name="label" xsi:type="string" translatable="true">Custom Country Resolver</item>
                <item name="value" xsi:type="const">Vendor\Module\Model\Country\Resolver\CustomCountryResolver::RESOLVER_CODE</item>
            </item>
        </argument>
    </arguments>
</virtualType>
```

The country resolver is ready to use.

## SEO and Performance

We strongly discourage you to use the first visit redirect as it is considered harmful and bad practice. Instead show a 
dialog to allow the visitor to select the country of its preference.  
This feature is available in our module [Opengento_CountryStoreSwitcher](https://github.com/opengento/magento2-country-store-switcher) `opengento/module-country-store-switcher`.

## Support

Raise a new [request](https://github.com/opengento/magento2-country-store-redirect/issues) to the issue tracker.

## Authors

- **Opengento Community** - *Lead* - [![Twitter Follow](https://img.shields.io/twitter/follow/opengento.svg?style=social)](https://twitter.com/opengento)
- **Thomas Klein** - *Maintainer* - [![GitHub followers](https://img.shields.io/github/followers/thomas-kl1.svg?style=social)](https://github.com/thomas-kl1)
- **Contributors** - *Contributor* - [![GitHub contributors](https://img.shields.io/github/contributors/opengento/magento2-country-store-redirect.svg?style=flat-square)](https://github.com/opengento/magento2-country-store-redirect/graphs/contributors)

## License

This project is licensed under the MIT License - see the [LICENSE](./LICENSE) details.

***That's all folks!***
