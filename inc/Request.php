<?php


namespace WooCustomTitles\Inc;


class Request
{
    private $api;

    public function __construct()
    {
        $this->api = new Api();
    }

    public function action_create_relation(int $category_id, array $attributes)
    {

        $this->api->create_relation($category_id, $attributes);
    }

    public function action_delete_relation(array $relation_ids)
    {
        $this->api->delete_relations($relation_ids);
    }
}