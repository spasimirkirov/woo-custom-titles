<?php


namespace WooCustomTitles\Inc;


class Api
{
    public $db;

    /**
     * Api constructor.
     */
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * @return int|\WP_Error|\WP_Term[]
     */
    public static function list_categories()
    {
        $db = new Database();
        return $db->select_all_categories();
    }

    /**
     * @return array
     */
    public static function list_distinct_meta_attributes()
    {
        $db = new Database();
        $distinct_meta_attributes = [];
        $meta_attributes = $db->select_product_attributes();
        foreach ($meta_attributes as $meta_attribute_array) {
            $attributes = unserialize($meta_attribute_array['_product_attributes']);
            foreach ($attributes as $attribute) {
                if (!in_array($attribute['name'], $distinct_meta_attributes) && !$attribute['is_taxonomy'])
                    $distinct_meta_attributes[] = $attribute['name'];
            }
        }
        return $distinct_meta_attributes;
    }

    public function create_relation(int $category_id, array $attributes)
    {
        if (!$relation = $this->db->select_custom_title_relations(['category_id' => $category_id, 'row' => 0])) {
            $this->db->insert_custom_title_relation($category_id, serialize($attributes));
        }
    }

    public function get_relations()
    {
        return $this->db->select_custom_title_relations();
    }

    public function delete_relations(array $relation_ids)
    {
        return $this->db->delete_custom_title_relation($relation_ids);
    }


}