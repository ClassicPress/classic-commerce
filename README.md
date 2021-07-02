This branch is for my pull request for issue [#320](https://github.com/ClassicPress-plugins/classic-commerce/issues/320).

# Classic Commerce [![Build Status](https://travis-ci.com/ClassicPress-plugins/classic-commerce.svg?branch=master)](https://travis-ci.com/ClassicPress-plugins/classic-commerce)

Welcome to the Classic Commerce repository on GitHub. This is a fork of WooCommerce and is still in development.

Classic Commerce is a simple, powerful and independent e-commerce platform. Sell anything with ease.

You can browse the source, look at open issues and keep track of development here.

We recommend all developers follow the [Issues](https://github.com/ClassicPress-plugins/classic-commerce/issues) and [Pull Requests](https://github.com/ClassicPress-plugins/classic-commerce/pulls) for the latest development updates.

## Roadmap
You can follow up on the development cycle by reading our [roadmap](https://github.com/ClassicPress-plugins/classic-commerce/wiki/Plugin-Roadmap).

## FAQs
**Can I install WooCommerce together with Classic Commerce?**
Yes, you can have both WooCommerce and Classic Commerce *installed* as plugins. However, you can't have them both *activated*. You will need to deactivate one before activating the other.

**What are the differences between WooCommerce and Classic Commerce?**
Classic Commerce is a fork of WooCommerce with all the WooCommerce Services and Jetpack integrations removed. This means there will be no "nags" or "upsells" at any stage when you are using Classic Commerce. You won't find any plugins or products that are promoted or pushed in any way.

**Can I import my products and settings from WooCommerce to Classic Commerce?**
There is no need to import anything when changing from WooCommerce to Classic Commerce. They share all the same classes and hooks so your existing WooCommerce records will be recognised by Classic Commerce and immediately available.

**What version of WooCommerce is Classic Commerce based on?**
Classic Commerce is a fork of WooCommerce 3.5.3.

**What plugins work with Classic Commerce?**
All of the extensions compatible with WooCommerce 3.5.3 should still be usable, provided they do not rely on Jetpack or WooCommerce Services. However, we strongly recommend that you test and monitor any extensions or plugins that you may need to provide extra functionality.

**How do I switch over from using WooCommerce to using Classic Commerce? Is it safe to uninstall WooCommerce?**
To try out Classic Commerce you can manually install it as a plugin. Make sure you deactivate WooCommerce before activating Classic Commerce. If you want to uninstall WooCommerce completely you can do so safely, but we recommend you take a full backup of your site first. Note that deactivating and deleting WooCommerce *normally* only removes the plugin and its files; the settings, orders, products, pages, etc are retained in the database. However, there is a possibility that a setting in your site’s wp-config file may over-ride this, so it is worth checking to make sure the following condition has not been included anywhere in that file: `define( 'WC_REMOVE_ALL_DATA', true);`

## Documentation
* [Classic Commerce Documentation](https://classiccommerce.cc/docs/)
* [Classic Commerce Code Reference](https://classiccommerce.cc/docs/)
* [WooCommerce REST API Docs](https://woocommerce.github.io/woocommerce-rest-api-docs/)

## Reporting Security Issues
To disclose a security issue to our team. (security@classicpress.net)

## Support
This repository is most suitable for development but can also be used for support. We will add a support tag to your issue. However, this might take a little longer to get a response. You can also use the dedicated support area on the [ClassicPress community forum](https://forums.classicpress.net/tags/classic-commerce/).

## Contributing to Classic Commerce
If you have a patch or have stumbled upon an issue with Classic Commerce core, you can contribute this back to the code. Please read our [contributor guidelines](https://github.com/ClassicPress-plugins/classic-commerce/blob/master/.github/CONTRIBUTING.md) for more information about how you can do this.

