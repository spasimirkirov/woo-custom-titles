<?php

function wct_activation_hook()
{
    flush_rewrite_rules();
    db_create_attribute_templates_table();
}

function wct_deactivation_hook()
{
    flush_rewrite_rules();
}

function wct_uninstallation_hook()
{
    flush_rewrite_rules();
    db_drop_wct_attribute_templates_table();
}

function wct_admin_menu()
{
    $main_page = add_menu_page('Woo Custom Titles', 'Woo Custom Titles', 'manage_options', 'wct_menu');
    $pages = [
        ['wct_menu', 'WCT - Generate', 'Titles', 'manage_options', 'wct_titles', 'wct_titles_page_callback'],
        ['wct_menu', 'WCT - Settings', 'Settings', 'manage_options', 'wct_settings', 'wct_settings_page_callback'],
    ];
    add_action('load-' . $main_page, 'wct_load_admin_scripts');
    foreach ($pages as $page) {
        $sub_page = add_submenu_page(...$page);
        add_action('load-' . $sub_page, 'wct_load_admin_scripts');
    }
}

function wct_titles_page_callback()
{
//    require_once plugin_dir_path(__FILE__) . 'template/titles.php';
}

function wct_settings_page_callback()
{
    require_once plugin_dir_path(__FILE__) . 'template/settings.php';
}

function wct_load_admin_scripts()
{
    add_action('admin_enqueue_scripts', 'wct_enqueue_bootstrap_scripts');
}

function wct_enqueue_bootstrap_scripts()
{
    wp_enqueue_style('st_bootstrap4_css', plugin_dir_url(__FILE__) . 'assets/css/bootstrap.min.css');
    wp_enqueue_script('st_jquery_slim_min', plugin_dir_path(__FILE__) . 'assets/css/jquery-3.5.1.slim.min.js', array('jquery'), '', true);
    wp_enqueue_script('st_popper_min', plugin_dir_path(__FILE__) . 'assets/css/popper.min.js', array('jquery'), '', true);
    wp_enqueue_script('st_bootstrap4_js', plugin_dir_path(__FILE__) . 'assets/css/bootstrap.min.js', array('jquery'), '', true);
}


/**
 * Hooks on product meta update
 * @param $meta_id
 * @param $post_id
 * @param $meta_key
 * @param $meta_value
 */
function wct_on_post_meta_update_hook($meta_id, $post_id, $meta_key, $meta_value)
{
    // loop trough _product_attributes upon product creation and create the new title template
//    if ($meta_key === '_product_attributes') {
//        foreach ($meta_value as $attribute) {
//        }
//    }
}