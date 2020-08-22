<?php


namespace WooCustomTitles\Inc;


class UserInterface extends WooTitlesPlugin
{
    public $pages;

    public function register_pages()
    {
        $this->pages['main'] = add_menu_page('Woo Custom Titles', 'Woo Custom Titles', 'manage_options', 'woo_custom_titles');
        $this->pages['sub'] = [
            ['woo_custom_titles', 'Woo Custom Titles', 'Релации', 'manage_options', 'woo_custom_titles', [$this, 'woo_custom_titles_page']],
            ['woo_custom_titles', 'Woo Custom Titles', 'Шаблони', 'manage_options', 'woo_custom_titles_templates', [$this, 'woo_custom_titles_templates_page']],
            ['woo_custom_titles', 'Woo Custom Titles Create', 'Създаване', 'manage_options', 'woo_custom_titles_create', [$this, 'woo_custom_titles_create_page']],
            ['woo_custom_titles', 'Woo Custom Titles Settings', 'Настройки', 'manage_options', 'woo_custom_titles_settings', [$this, 'woo_custom_titles_settings_page']],
        ];
    }

    public function woo_custom_titles_page()
    {
        require_once $this->template_path . '/list.php';
    }

    public function woo_custom_templates_page()
    {
        require_once $this->template_path . '/templates.php';
    }

    public function woo_custom_titles_create_page()
    {
        require_once $this->template_path . '/create.php';
    }

    public function woo_custom_titles_settings_page()
    {
        require_once $this->template_path . '/settings.php';
    }

    public function load_scripts()
    {
        add_action('load-' . $this->pages['main'], [$this, 'enqueue_scripts']);
        foreach ($this->pages['sub'] as $page) {
            $sub_page = add_submenu_page(...$page);
            add_action('load-' . $sub_page, [$this, 'enqueue_scripts']);
        }
    }

    public function enqueue_scripts()
    {
        wp_enqueue_style('bootstrap4_css', $this->plugin_url . 'assets/css/bootstrap.min.css');
        wp_enqueue_script('jquery_slim_min', $this->plugin_url . 'assets/js/jquery-3.5.1.slim.min.js', array('jquery'), '', true);
        wp_enqueue_script('popper_min', $this->plugin_url . 'assets/js/popper.min.js', array('jquery'), '', true);
        wp_enqueue_script('bootstrap4_js', $this->plugin_url . 'assets/js/bootstrap.min.js', array('jquery'), '', true);
        wp_enqueue_script('plugin_main_js', $this->plugin_url . 'assets/js/main.js', '', '', true);
    }
}