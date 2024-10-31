# PPM Fulfillment - WooCommerce

This plugin allows WooCommerce orders to be fulfilled through [PPM
Fulfillment](https://home.ppmfulfillment.com/), which is a third party
fulfillment center focusing on medical devices and home medical device resupply
(e.g. sending out CPAP machines and parts thereof).

You can:

1. Configure your plugin settings
2. Mark products to be fulfilled by PPM Fulfillment, including providing a
   PPM-specific product ID/SKU.
3. Receive tracking updates from PPM on a per-order basis
4. See lot and serial numbers for items shipped (if applicable)

## Requirements

In order to use it, you'll need:

1. WooCommerce installed
2. An active account with PPM Fulfillment
3. Your PPM Fulfillment API Key
4. Your PPM Fulfillment owner code.

## Installation

You can install this plugin in two ways.

1. You can download (as a zip) from the [GitHub
   Repository](https://github.com/PPM-Fulfillment/ppm-woocommerce) by clicking
   the "Clone or Download" button, then selecting "Download as ZIP". This will
   download a compressed `.zip` file. Unpackage it into `wp-content/plugins`
   (making sure all the the contents are inside a folder called
   `ppm-woocommerce`).
2. You can get it from the Wordpress Marketplace by searching for "PPM
   WooCommerce" off the Plugins page in your Wordpress Admin Dashboard.

Either way, once you get the files onto your system, you'll need to *Activate*
the plugin, which is as simple as clicking the "Activate" link on the Plugins
dashboard.

Upon doing this, a "PPM Options" item will be available in your Wordpress menu:

![](assets/ppm_options_menu_link.png)

**You must have WooCommerce also installed to use this plugin.** Without an
active WooCommerce installation, this plugin will sit happily doing nothing.

## Configuration

Your first step is to configure the plugin itself. You can do this from the "PPM
Options" page, which looks like this. Use the values PPM supplies to you:

![](assets/ppm_plugin_setup.png)

Once this step is complete, you can begin configuring individual products. Under
the WooCommerce page, go to "Products" and then select an individual product.
Down in the "Inventory" area of the product configuration, you'll see two
fields:

+ PPM SKU
+ Fulfilled by PPM?

You must check the "Fulfilled by PPM?" box if you want orders involving this
item to be sent to PPM. If no PPM SKU is provided, we use whatever SKU you set.
Whatever SKU you use, it needs to match what PPM has in their database for the
item to be fulfillable.

Any product not marked "Fulfilled by PPM" *will not be sent* to PPM's system.
The plugin only passes along orders with 1 or more products meant to be
fulfilled by PPM.

The last thing you'll need to do is provide a webhook URL to PPM's support team.
This lets shipping updates be sent to your site.

Your webhook URL will be `www.yoursite.com/wp-json/ppm/v1/update-shipments` (but
with your actual site in place of `www.yoursite.com`).

## Usage

Once you configure the plugin and configure one or more products, the plugin
itself is largely invisible. Every time an order is submitted to PPM, the status
of that submission (either "received successfully" or "not sent successfully")
will be appended to the order as a note. At this time we chose to not update
order statuses to keep from conflicting with other plugins or workflows you may
have.

![](/assets/order_notes.png)

When you receive tracking updates to an order, those will be added as a note to
the order. You'll see a tracking number as well as line-items for a given
shipment, including lot and serial numbers:

![](assets/tracking_number.png)

![](assets/product_update.png)

## Support

If you have any questions about this plugin, if you need help getting it set up,
or to report an issue, please contact [PPM's Support
Line](https://home.ppmfulfillment.com/contact-us/) or talk to your contacts
there directly.
