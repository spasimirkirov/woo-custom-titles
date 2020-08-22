<?php


namespace WooCustomTitles\Inc;


class WooTitlesPlugin
{
    protected $plugin_url;
    protected $plugin_path;
    protected $template_path;

    public function __construct()
    {
        $this->plugin_url = plugin_dir_url(__FILE__);
        $this->plugin_path = plugin_dir_path(__FILE__);
        $this->template_path = plugin_dir_path(__FILE__) . 'template';
    }

    public function init()
    {
        if (get_option('woo_custom_titles_autogenerate', false))
            add_action('the_title', [$this, 'shorten_woo_product_title'], 10, 4);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_menu', [$this, 'register_interface']);
    }

    public function register_settings()
    {
        register_setting('woo_custom_titles_options_group', 'woo_custom_titles_autogenerate', [
            'type' => 'boolean',
            'description' => 'Enable/Disable title auto generate upon product view',
            'default' => false
        ]);
        add_settings_section('woo_custom_titles_configuration', 'Settings', '', 'woo_custom_titles_settings');
        add_settings_field(
            'woo_custom_titles_autogenerate',
            'Генериране на персоналирано заглавие за продукти?',
            function () {
                echo '<input type="checkbox" name="woo_custom_titles_autogenerate" value="1" ' . checked('1', get_option('woo_custom_titles_autogenerate', false), false) . '/>';
            },
            'woo_custom_titles_settings',
            'woo_custom_titles_configuration',
            ['label_for' => 'woo_custom_titles_autogenerate']
        );
    }

    public function register_interface()
    {
        $interfaceService = new UserInterface();
        $interfaceService->register_pages();
        $interfaceService->load_scripts();
    }

    public function shorten_woo_product_title($title, $post_id)
    {
        if (get_post_type($post_id) === 'product') {
            $db = new Database();
            $terms = get_the_terms($post_id, 'product_cat')[0];
            $relation = $db->select_custom_title_relations(['category_id' => $terms->term_id, 'row' => 0]);
            if (!$relation)
                return $title;
            $relation_attributes = array_filter(unserialize($relation['attributes']));
            $_product_attributes = unserialize($db->select_product_attributes(['post_id' => $post_id])['_product_attributes']);
            $new_title = array_map(function ($attribute_name) use ($_product_attributes) {
                $i = array_search($attribute_name, array_column($_product_attributes, 'name'), false);
                return $i === false ? $i : $_product_attributes[$i]['value'];
            }, $relation_attributes);
            $title = implode(' ', $new_title);
        }

        return $title;
    }
}