<?php

/**
 * Register POST /wp-json/ppm/v1/update-shipments, with a body like the following:
 *
 *  {
 *   "OrderId": "000000001",
 *   "TrackingNumber": "trackNumber",
 *   "Carrier": "carrier",
 *   "LineItems": [
 *     { "ProductId": "ppmsku", "Quantity": 1, "LotNumber": "12345", "SerialNumber": "ABCXYZ" },
 *     { "ProductId": "24-MB04", "Quantity": 2, "LotNumber": "678", "SerialNumber": "" },
 *   ]
 * }
 *
 * Upon receiving an update, we:
 * 1. Fetch the order
 * 2. Add tracking info
 * 3. Fetch Line Items
 * 4. Add notes around lot number and serial number
 */

add_action("rest_api_init", function() {
  register_rest_route(
    "ppm/v1/",
    "/update-shipments",
    array(
      "methods" => "POST",
      "callback" => "update_ppm_tracking_info"
    )
  );
});

function update_ppm_tracking_info(WP_REST_REQUEST $request) {
  $order_id = $request["OrderId"];
  $tracking_number = $request["TrackingNumber"];
  $carrier = $request["Carrier"];

  $order = wc_get_order($order_id);

  if(empty($order)) {
    error_log("BOMBED OUT ON ORDER: " . $order_id);
    $response = new WP_REST_Response(array("success" => FALSE));
    $response->set_status(404);
    return $response;
  }

  // WooCommerce Shipment Tracking
  if(function_exists("wc_st_add_tracking_number")) {
    // The OMS sends us "FedEx"; AfterShip and WST expect "Fedex", otherwise
    // tracking info breaks.

    if($carrier == "FedEx") {
      $carrier = "Fedex";
    }

    wc_st_add_tracking_number(
      $order_id,
      $tracking_number,
      $carrier,
      date("Y-m-d")
    );
  }

  // Advanced Shipment Tracking
  if(class_exists("WC_Advanced_Shipment_Tracking_Actions")) {
    $trackingAction = WC_Advanced_Shipment_Tracking_Actions::get_instance();
    $trackingArgs = array(
      "tracking_provider" => $carrier,
      "tracking_number" => $tracking_number,
      "status_shipped" => 2,
      "date_shipped" => date("Y-m-d")
    );
    $trackingAction->insert_tracking_item($order_id, $trackingArgs);
  }

  $carrier_string = "\nCarrier: " . $carrier;
  $tracking_string = "Tracking Number: " . $tracking_number;
  $note = "Received Tracking Information." . $carrier_string . "\n" . $tracking_string;
  $order->add_order_note($note);

  $mark_as_completed = true;

  // Get all order items so we can match to line items in POST body
  $order_items = array();
  foreach($order->get_items() as $item_id => $item) {
    $product = $item->get_product();

    if(is_object($product)) {
      $ppm_sku = get_post_meta($product->get_id(), "ppm_sku", true);

      $sku = $product->get_sku();

      if(empty($ppm_sku)) {
        $mark_as_completed = false;
      } else {
        // Set fallback SKU for querying
        $sku = $ppm_sku;
      }

      $name = $item["name"];
      $order_items[] = array(
        "sku" => $sku,
        "name" => $name,
      );
    }
  }

  $line_items = $request["LineItems"];

  // { "ProductId": "", "Quantity": "", "SerialNumber": "", "LotNumber": ""}
  foreach($line_items as $item) {
    $product_id = $item["ProductId"];

    $tmp_product_index = array_search($product_id, array_column($order_items, "sku"));

    if($tmp_product_index === false) {
      $note = "WARNING: " . $tracking_string .
        "\n" . "Received submission for product " .
        $product_id . ". Not found. Quantity: " . $item["Quantity"];
      $order->add_order_note($note);
      continue;
    }

    $tmp_product = $order_items[$tmp_product_index];

    $quantity = $item["Quantity"];
    $serial_number = $item["SerialNumber"];
    $lot_number = $item["LotNumber"];

    $product_name = $tmp_product["name"] . " (" . $product_id . ")";
    $qty_string = "\nQuantity: " . $quantity;

    $lot_string = "\nLot Number: None";
    if(!empty($lot_number)) {
      $lot_string = "\nLot Number: " . $lot_number;
    }

    $serial_string = "\nSerial Number: None";
    if(!empty($serial_number)) {
      $serial_string = "\nSerial Number: " . $serial_number;
    }

    $note_text = $tracking_string . "\n" . $product_name . $qty_string . $lot_string . $serial_string;

    $order->add_order_note($note_text);
  }

  $response = new WP_REST_Response(array("success" => TRUE));
  $response->set_status(200);
  return $response;
}
