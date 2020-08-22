<?php

namespace WooCustomTitles\Inc;

class Database
{
    public function wpdb()
    {
        global $wpdb;
        return $wpdb;
    }

    function create_title_templates_table()
    {
        $charset_collate = $this->wpdb()->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->wpdb()->base_prefix}woo_custom_title_relations` (
        `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	    `category_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
	    `attributes` LONGTEXT NULL DEFAULT NULL COLLATE 'utf8_general_ci',
        PRIMARY KEY  (`id`),
	    UNIQUE INDEX `UNIQUE KEY` (`category_id`)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    function drop_title_templates_table()
    {
        $this->wpdb()->query("DROP TABLE IF EXISTS `{$this->wpdb()->base_prefix}woo_custom_title_relations`");
    }

    function select_custom_title_relations(array $params = [])
    {
        if (!isset($params['output']))
            $params['output'] = 'ARRAY_A';

        $sql = "SELECT a.`id`, a.`category_id`, b.`name` as `category_name`, a.`attributes` FROM `{$this->wpdb()->base_prefix}woo_custom_title_relations` as a";
        $sql .= " INNER JOIN `{$this->wpdb()->base_prefix}terms` as b ON a.`category_id` = b.`term_id`";

        $sql .= isset($params['id']) ?
            $this->wpdb()->prepare(" WHERE `id` = '%d'", $params['id']) :
            $this->wpdb()->prepare(" WHERE `id` > '0'");

        if (isset($params['category_id']))
            $sql .= $this->wpdb()->prepare(" AND `category_id` = '%d'", $params['category_id']);

        if (isset($params['col']))
            return $this->wpdb()->get_col($sql, $params['col']);

        if (isset($params['row']))
            return $this->wpdb()->get_row($sql, $params['output'], $params['row']);

        return $this->wpdb()->get_results($sql, $params['output']);
    }

    function insert_custom_title_relation($category_id, $attributes)
    {
        $sql = $this->wpdb()->prepare("INSERT INTO `{$this->wpdb()->base_prefix}woo_custom_title_relations` (`category_id`, `attributes`) VALUES ('%d','%s');", $category_id, $attributes);
        return $this->wpdb()->query($sql);
    }

    function delete_custom_title_relation(array $relation_ids)
    {
        $sql = "DELETE FROM `{$this->wpdb()->base_prefix}woo_custom_title_relations` WHERE `category_id` IN(" . implode(",", $relation_ids) . ");";
        return $this->wpdb()->query($sql);
    }

    public function select_all_categories()
    {
        return get_terms([
            'taxonomy' => "product_cat"
        ]);
    }

    public function select_product_attributes($params = [])
    {
        $prefix = $this->wpdb()->base_prefix;
        $sql = "SELECT `post_id`, `meta_value` as `_product_attributes` FROM `{$prefix}postmeta` WHERE meta_key ='_product_attributes'";
        $sql .= isset($params['post_id']) ?
            $this->wpdb()->prepare("AND `post_id` = '%s'", $params['post_id']) : " AND `post_id` IN (SELECT ID FROM `{$prefix}posts` WHERE `post_type` = 'product')";
        return isset($params['post_id']) ? $this->wpdb()->get_row($sql, 'ARRAY_A', 0) : $this->wpdb()->get_results($sql, 'ARRAY_A');
    }

}

