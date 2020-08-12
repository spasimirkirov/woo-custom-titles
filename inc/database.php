<?php

namespace WooCustomTitles\Inc;

class Database
{
    public function wpdb()
    {
        global $wpdb;
        return $wpdb;
    }

    function create_attribute_templates_table()
    {
        $charset_collate = $this->wpdb()->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->wpdb()->base_prefix}woo_custom_title_attributes` (
        `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	    `category_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
        `name` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
        PRIMARY KEY  (`id`)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    function create_title_templates_table()
    {
        $charset_collate = $this->wpdb()->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->wpdb()->base_prefix}woo_custom_title_templates` (
        `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	    `post_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
        `title` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
        PRIMARY KEY  (`id`),
	    UNIQUE INDEX `UNIQUE KEY` (`post_id`)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    function drop_title_templates_table()
    {
        $this->wpdb()->query("DROP TABLE IF EXISTS `{$this->wpdb()->base_prefix}woo_custom_title_templates`");
    }

    function drop_title_attributes_table()
    {
        $this->wpdb()->query("DROP TABLE IF EXISTS `{$this->wpdb()->base_prefix}woo_custom_title_attributes`");
        delete_option("wct_auto_generate");
    }

    function select_product_attributes()
    {
        $sql = "SELECT `post_id`,`meta_value` FROM `wp_postmeta` WHERE `post_id` IN (SELECT ID FROM `wp_posts` WHERE `post_type` = 'product') AND meta_key ='_product_attributes';";
        return $this->wpdb()->get_results($sql, 'ARRAY_A');
    }

    function select_title_template(array $params = [])
    {
        if (!isset($params['output']))
            $params['output'] = 'ARRAY';

        $query = "SELECT * FROM `{$this->wpdb()->base_prefix}woo_custom_title_templates`";
        $query .= isset($params['id']) ?
            $this->wpdb()->prepare(" WHERE `id` = '%d'", $params['id']) :
            $this->wpdb()->prepare(" WHERE `id` > '0'");

        if (isset($params['post_id']))
            $query .= $this->wpdb()->prepare(" AND `post_id` = '%d'", $params['post_id']);

        if (isset($params['title']))
            $query .= $this->wpdb()->prepare(" AND `title` = '%s'", $params['title']);

        if (isset($params['active']))
            $query .= $this->wpdb()->prepare(" AND `active` = '%d'", $params['active']);

        return $params['output'] === 'ARRAY' ?
            $this->wpdb()->get_results($query, 'ARRAY_A') :
            $this->wpdb()->get_row($query, 'ARRAY_A');
    }

    function insert_title_template($post_id, $title)
    {
        $sql = $this->wpdb()->prepare("INSERT INTO `{$this->wpdb()->base_prefix}woo_custom_title_templates` (`post_id`, `name`) VALUES ('%d','%s');", $post_id, $title);
        $this->wpdb()->query($sql);
    }
}

