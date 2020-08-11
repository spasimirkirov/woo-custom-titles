<?php

function db_create_attribute_templates_table()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->base_prefix}wct_title_templates` (
        `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	    `post_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
        `title` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
        PRIMARY KEY  (id),
	    UNIQUE INDEX `UNIQUE KEY` (`post_id`)
        ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    update_option("wct_auto_generate", false);
}

function db_drop_wct_attribute_templates_table()
{
    global $wpdb;
    $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->base_prefix}wct_title_templates`");
    delete_option("wct_auto_generate");
}

function db_select_product_attributes()
{
    global $wpdb;
    $sql = "SELECT `post_id`,`meta_value` FROM `wp_postmeta` WHERE `post_id` IN (SELECT ID FROM `wp_posts` WHERE `post_type` = 'product') AND meta_key ='_product_attributes';";
    return $wpdb->get_results($sql, 'ARRAY_A');
}

function db_select_title_template(array $params = [])
{
    global $wpdb;
    if (!isset($params['output']))
        $params['output'] = 'ARRAY';

    $query = "SELECT * FROM `{$wpdb->base_prefix}wct_title_templates`";
    $query .= isset($params['id']) ?
        $wpdb->prepare(" WHERE `id` = '%d'", $params['id']) :
        $wpdb->prepare(" WHERE `id` > '0'");

    if (isset($params['post_id']))
        $query .= $wpdb->prepare(" AND `post_id` = '%d'", $params['post_id']);

    if (isset($params['title']))
        $query .= $wpdb->prepare(" AND `title` = '%s'", $params['title']);

    if (isset($params['active']))
        $query .= $wpdb->prepare(" AND `active` = '%d'", $params['active']);

    return $params['output'] === 'ARRAY' ?
        $wpdb->get_results($query, 'ARRAY_A') :
        $wpdb->get_row($query, 'ARRAY_A');
}

function db_insert_title_template($post_id, $title)
{
    global $wpdb;
    $sql = $wpdb->prepare("INSERT INTO `{$wpdb->base_prefix}wct_title_templates` (`post_id`, `name`) VALUES ('%d','%s');", $post_id, $title);
    $wpdb->query($sql);
}
