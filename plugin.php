<?php
/*
Plugin Name: Woo Custom Titles
Plugin URI: https://github.com/spasimirkirov/woo-custom_titles
Description: Generates WooCommerce title from product's attributes
Version: 1.0.0
Author: Spasimir Kirov
Author URI: https://www.vonchronos.com/
License: GPLv2 or later
Text Domain: woo-custom-titles
 */

if (!defined('ABSPATH')) {
    die;
}

define('BASE_PATH', plugin_dir_path(__FILE__));
define('BASE_URL', plugin_dir_url(__FILE__));

// include the Composer autoload file
require plugin_dir_path(__FILE__) . 'vendor/autoload.php';


function woo_custom_titles_activation()
{
    $db = new \WooCustomTitles\Inc\Database();
    $db->create_title_templates_table();
    update_option("woo_custom_titles_autogenerate", false);
    flush_rewrite_rules();
}

function woo_custom_titles_deactivation()
{
    flush_rewrite_rules();
}

function woo_custom_titles_uninstall()
{
    $db = new \WooCustomTitles\Inc\Database();
    $db->drop_title_templates_table();
    delete_option("woo_custom_titles_autogenerate");
}

$dependencies = [
    'woocommerce/woocommerce.php' => 'WooCommerce',
    'woo-product-attributes/plugin.php' => 'Woo Product Attributes',
];

$errors = 0;
foreach ($dependencies as $plugin_file => $plugin_name) {
    $is_plugin_active = in_array($plugin_file, apply_filters('active_plugins', get_option('active_plugins')));
    if (!$is_plugin_active) {
        add_action('admin_notices', function () use ($plugin_name) {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p><?php _e('' . $plugin_name . ' е неактивен, моля активирайте го преди да използвате Woo Custom Titles'); ?></p>
            </div>
            <?php
        });
        $errors++;
    }
}
if ($errors === 0) {
    register_activation_hook(__FILE__, 'woo_custom_titles_activation');
    register_deactivation_hook(__FILE__, 'woo_custom_titles_deactivation');
    register_uninstall_hook(__FILE__, 'woo_custom_titles_uninstall');
    $WooAttributePlugin = new \WooCustomTitles\Inc\WooTitlesPlugin();
    $WooAttributePlugin->init();
}
