<?php

/**
 * Upon an order being placed, we need to:
 * + Determine if it has any PPM items
 * + If so, grab just those items and quantities
 * + Use PPM SKU or fallback to regular SKU
 * + Package everything up and ship it to PPM
 *
 * We add a note to the order if there are any interactions with PPM's API.
 */

function ppm_submit_order($order_id)
{
    $order = wc_get_order($order_id);

    $url = get_option("ppm_woo_api_url");
    $apiKey = get_option("ppm_woo_api_key");
    $ownerCode = get_option("ppm_woo_owner_code");

    // Exit early unless we actually can send anything.
    if(empty($url) || empty($apiKey) || empty($ownerCode)) {
        return null;
    }

    $process = curl_init();

    // Build our Line Items
    $items = array();
    foreach($order->get_items() as $item_id => $item) {
        $product = $item->get_product();
        $product_sku = null;

        if(is_object($product)) {
            $product_id = $product->get_id();
            $product_sku = $product->get_sku();

            $ppm_fulfilled_by = get_post_meta($product_id, "ppm_fulfilled_by", true);

            if($ppm_fulfilled_by == "yes") {
                $sku = get_post_meta($product_id, "ppm_sku", true);

                if(empty($sku)) {
                    $sku = $product_sku;
                }

                $items[] = array(
                    "ProductId" => $sku,
                    "Quantity" => wc_stock_amount($item["qty"]),
                    "Description" => $item["name"],
                );
            }
        }
    }

    // Shipping Method must be on of the approved entries from this list:
    // https://app.swaggerhub.com/apis-docs/PPM-Fulfillment/PPMCustomerExternalAPI/1.0.0#/SubmitOrderModel
    // We re-map as best we can.
    $shippingMethod = "";
    if(preg_match("/^ground/i", $order->get_shipping_method())) {
        $shippingMethod = "Ground Delivery";
    }

    if (count($items) == 0) {
        return;
    }

    $args = array(
        "orderId" => $order_id,
        "orderNumber" => $order_id,
        "ownerCode" => $ownerCode,
        "shipToName" => $order->get_formatted_shipping_full_name(),
        "shipToPhoneNumber" => $order->get_billing_phone(),
        "address1" => $order->get_shipping_address_1(),
        "address2" => $order->get_shipping_address_2(),
        "city" => $order->get_shipping_city(),
        "state" => $order->get_shipping_state(),
        "zipCode" => $order->get_shipping_postcode(),
        "shippingMethod" => $shippingMethod,
        "lineItems" => $items
    );

    $curlOptions = array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . $apiKey,
            "Content-Type: application/json"
        ],
        CURLOPT_POST => TRUE,
        CURLOPT_POSTFIELDS => json_encode($args),
        CURLOPT_SSL_VERIFYPEER => FALSE
    );

    curl_setopt_array($process, $curlOptions);

    $resultBody = curl_exec($process);

    $success = "true";
    $note = "";

    if (!curl_errno($process)) {
        switch ($http_code = curl_getinfo($process, CURLINFO_RESPONSE_CODE)) {
            case 200:
            case 201:
                $note = __("Successfully posted to PPM Fulfillment");
                break;
            default:
                $note = __("Failed to post to PPM Fulfillment - Please contact PPM support.");
                $success = "false";

        }
    } else {
        $success = "false";
        $note = __("Failed to connect to PPM Fulfillment - Error Code: " . curl_errno($process) . ". Please contact PPM support.");
    }

    curl_close($process);

    $order->add_order_note($note);

    return array(
        "body" => $resultBody,
        "success" => $success,
        "url" => $url
    );
}

add_action("woocommerce_order_status_processing", "ppm_submit_order", 10, 1);
