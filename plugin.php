<?php
/*
Plugin Name: Woo Custom Titles
Plugin URI: https://github.com/spasimirkirov/woo-title-generator
Description: Generates WooCommerce title from product's attributes
Version: 1.0.0
Author: Spasimir Kirov
Author URI: https://www.vonchronos.com/
License: GPLv2 or later
Text Domain: woo-attribute-generator
 */

if (!defined('ABSPATH')) {
    die;
}

class WooTitlePlugin
{
    private $plugin_path;
    private $includes_path;

    public function __construct()
    {
        $this->plugin_path = plugin_dir_path(__FILE__);
        $this->includes_path = $this->plugin_path . 'inc';
    }

    function init()
    {
        $this->includes();
//        if (get_option('wct_auto_generate', false))
//            add_action('added_post_meta', 'wct_on_post_meta_update_hook', 10, 4);
        add_action('admin_menu', 'wct_admin_menu');
        add_action('admin_init', [$this, 'settings']);
    }

    function includes()
    {
        require_once $this->includes_path . '/database.php';
        require_once $this->includes_path . '/callbacks.php';
    }

    function settings()
    {
        register_setting('wct_option_group', 'wct_auto_generate', [
            'type' => 'boolean',
            'description' => 'Enable/Disable title auto generate upon product import',
            'default' => false
        ]);

        add_settings_section('wct_plugin_configuration', 'Settings', '', 'wct_settings');

        add_settings_field(
            'wct_auto_generate',
            'Генериране на термини при вкарване на продукт?',
            function () {
                echo '<input type="checkbox" name="wct_auto_generate" value="1" ' . checked('1', get_option('wct_auto_generate'), false) . '/>';
            },
            'wct_settings',
            'wct_plugin_configuration',
            ['label_for' => 'wct_auto_generate']
        );
    }
}

register_activation_hook(__FILE__, 'wct_activation_hook');
register_deactivation_hook(__FILE__, 'wct_deactivation_hook');
register_uninstall_hook(__FILE__, 'wct_uninstallation_hook');

$is_woo_active = in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')));
if (!$is_woo_active) {
    add_action('admin_notices', function () {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p><?php _e('WooCommerce е неактивен, моля активирайте го преди да използвате Woo Attribute Generator'); ?></p>
        </div>
        <?php
    });
} else {
    $WooAttributePlugin = new WooTitlePlugin();
    $WooAttributePlugin->init();
}
