=== Plugin Name ===
Contributors: ppmfulfillment
Tags: fulfillment
Requires at least: 5.3
Tested up to: 5.4
Stable tag: trunk
Requires PHP: 7
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Fulfill WooCommerce orders through PPM Fulfillment

== Description ==

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

If you have any questions about this plugin, if you need help getting it set up,
or to report an issue, please contact [PPM's Support
Line](https://home.ppmfulfillment.com/contact-us/) or talk to your contacts
there directly.

== Installation ==

You can install this plugin in two ways.

1. You can download (as a zip) from the [GitHub
Repository](https://github.com/PPM-Fulfillment/ppm-woocommerce) by clicking the
"Clone or Download" button, then selecting "Download as ZIP". This will download
a compressed `.zip` file. Unpackage it into
`wp-content/plugins/ppm-woocommerce`.
2. You can get it from the Wordpress Marketplace by searching for "PPM
WooCommerce" off the Plugins page in your Wordpress Admin Dashboard.

Either way, once you get the files onto your system, you'll need to *Activate*
the plugin, which is as simple as clicking the "Activate" link on the Plugins
dashboard.

Upon doing this, a "PPM Options" item will be available in your Wordpress menu:

![](assets/ppm_options_menu_link.png)

== Frequently Asked Questions ==

= Can I use this without a PPM Fulfillment Account? =

Unfortunately, no. But you can contact PPM Fulfillment to get set up for
third-party fulfillment with them at:
https://home.ppmfulfillment.com/contact-us/

== Screenshots ==

== Changelog ==

= 0.1.11 =
* Further reversion to order completion logic

= 0.1.10 =
* Revert order completion logic

= 0.1.9 =
* Fix order completion on mixed PPM/non-PPM order items

= 0.1.8 =
* Fix order status bug
* Fix FedEx carriers with Aftership and WooCommerce Shipment Tracking

= 0.1.7 =
* Better Advanced Shipment Tracking integration
* Integration with Woocommerce Shipment Tracking

= 0.1.6 =
* Add Advanced Shipment Tracking integration

= 0.1.5 =
* Add ability to handle product variants

= 0.1.4 =
* Better handle shipping options

= 0.1.3 =
* Change WP HTTP client to Curl; This resolves issues with JSON encoding

= 0.1.2 =
* Minor fixes to workflow

= 0.1.0 =
* Feature-complete release

== Upgrade Notice ==

None yet.
