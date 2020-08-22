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
        $rows = $this->api->create_relation($category_id, $attributes);
        $rows > 0 ?
            show_message('<div class="updated notice notice-success is-dismissible"><p>Успешно създаване на релация</p></div>') :
            show_message('<div class="error notice notice-error is-dismissible"><p>Грешка при създаване на релацията</p></div>');
    }

    public function action_delete_relation(array $relation_ids)
    {
        $this->api->delete_relations($relation_ids);
    }
}