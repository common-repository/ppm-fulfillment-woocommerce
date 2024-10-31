<?php
/**
* Set up options, etc., for the options menu in the admin
*/
function ppm_woo_admin_menu() {
    add_menu_page(
        __("PPM Options", "ppm-woo"),
        __("PPM Options", "ppm-woo"),
        "manage_options",
        "ppm-woo-options",
        "ppm_woo_contents"
    );
}

/**
 * Render the application settings form
 */
function ppm_woo_contents() {
    ?>
    <h1>PPM Fulfillment Options</h1>
    <form method="POST" action="options.php">
    <?php
    settings_fields("ppm-woo-options");
    do_settings_sections("ppm-woo-options");
    submit_button();
    ?>
    </form>
    <?php
}

/**
 * Create the settings form and populate fields
 */
function ppm_woo_settings_init() {
    add_settings_section(
        "ppm_woo_settings_section",
        __("PPM Configuration", "ppm-woo"),
        "ppm_settings_section_callback_function",
        "ppm-woo-options"
    );
    
    add_settings_field(
        "ppm_woo_api_key",
        __("PPM Fulfillment API Key", "ppm-woo"),
        "ppm_woo_api_key_markup",
        "ppm-woo-options",
        "ppm_woo_settings_section"
    );
    add_settings_field(
        "ppm_woo_owner_code",
        __("PPM Fulfillment Owner Code", "ppm-woo"),
        "ppm_woo_owner_code_markup",
        "ppm-woo-options",
        "ppm_woo_settings_section"
    );
    add_settings_field(
        "ppm_woo_api_url",
        __("PPM Fulfillment API URL", "ppm-woo"),
        "ppm_woo_api_url_markup",
        "ppm-woo-options",
        "ppm_woo_settings_section"
    );
    
    register_setting("ppm-woo-options", "ppm_woo_api_key");
    register_setting("ppm-woo-options", "ppm_woo_owner_code");
    register_setting("ppm-woo-options", "ppm_woo_api_url");
}

function ppm_settings_section_callback_function() {
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        echo "<p>Set your config values for PPM Fulfillment</p>";
    } else {
        echo "<p>You do not currently have WooCommerce Installed; this plugin will do nothing until WooCommerce is installed";
    }
}

function ppm_woo_api_key_markup() {
    ppm_woo_generic_field_markup("ppm_woo_api_key", "PPM Fulfillment API Key");
}

function ppm_woo_owner_code_markup() {
    ppm_woo_generic_field_markup("ppm_woo_owner_code", "PPM Fulfillment Owner Code");
}

function ppm_woo_api_url_markup() {
    ppm_woo_generic_field_markup(
        "ppm_woo_api_url", 
        "PPM Fulfillment API URL", 
        "https://portal.ppmfulfillment.com/api/External/ThirdPartyOrders"
    );
}

function ppm_woo_generic_field_markup($field_id, $field_name, $default="") {
    ?>
    <label for="<?php echo $field_id; ?>"><?php _e($field_name, "ppm-woo"); ?></label>
    <input 
    type="text" 
    id="<?php echo $field_id; ?>" 
    name="<?php echo $field_id; ?>"
    value="<?php echo get_option($field_id, $default); ?>"
    >
    <?php
}

add_action("admin_menu", "ppm_woo_admin_menu");
add_action("admin_init", "ppm_woo_settings_init");