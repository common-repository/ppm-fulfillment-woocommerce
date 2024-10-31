<?php
/**
* Plugin Name: PPM Fulfillment - Woo Commerce
* Plugin URI: https://github.com/PPM-Fulfillment/ppm-woocommerce
* Description: Fulfill your WooCommerce orders through PPM Fulfillment. Requires WooCommerce to be installed
* Author: Andrew Ek
* Text Domain: ppm-woo
* Version: 0.1.11
*/

// Prevent data leaks
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define("PPM_WOOCOMMERCE_VERSION", "0.1.11");

// Admin Options/Config page
include_once("ppm-options.php");
include_once("ppm-product-options.php");
include_once("ppm-order-submission-client.php");
include_once("ppm-shipping-api-endpoint.php");
