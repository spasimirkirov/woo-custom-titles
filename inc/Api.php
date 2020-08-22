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

    public function create_relation(int $category_id, array $attributes)
    {
        if (!$relation = $this->db->select_custom_title_relations(['category_id' => $category_id, 'row' => 0])) {
            return $this->db->insert_custom_title_relation($category_id, serialize($attributes));
        }
        return 0;
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