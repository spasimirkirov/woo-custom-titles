<?php

if (isset($_POST['submit_relation_action'])) {
    $requestApi = new \WooCustomTitles\Inc\Request();
    if ($_POST['action'] === 'none')
        show_message('<div class="error notice notice-error is-dismissible"><p>Не сте посочили действие</p></div>');

    if ($_POST['action'] === 'delete') {
        (!isset($_POST['relation_ids']) || empty($_POST['relation_ids'])) ?
            show_message('<div class="error notice notice-error is-dismissible"><p>Не сте посочили релации за изтриване</p></div>') :
            $requestApi->action_delete_relation($_POST['relation_ids']);
    }
}

$api = new \WooCustomTitles\Inc\Api();
$available_relations = $api->get_relations();
?>
<div class="container my-2">
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs p-0">
                <li class="nav-item">
                    <a class="nav-link active" href="<?= admin_url('admin.php?page=woo_custom_titles'); ?>">Релации</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                       href="<?= admin_url('admin.php?page=woo_custom_titles_create'); ?>">Добави</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                       href="<?= admin_url('admin.php?page=woo_custom_titles_settings'); ?>">Настройки</a>
                </li>
            </ul>
            <div class="card-header bg-dark text-light">
                Управление на параметрите за персонални заглавия
            </div>
        </div>
        <div class="form-group col-12">
            <form action="" method="post">
                <table class="table table-bordered table-hover">
                    <tr>
                        <th>
                            <label>
                                <input id="checkbox_select_all" type="checkbox">
                                Категория
                            </label>
                        </th>
                        <th>Мета атрибути</th>
                    </tr>
                    <?php foreach ($available_relations as $i => $relation): ?>
                        <tr>
                            <td>
                                <label for="taxonomy_<?= $i ?>">
                                    <input class="checkbox-taxonomy" type="checkbox" name="relation_ids[]"
                                           value="<?= $relation['id'] ?>">
                                    <?= $relation['category_name'] ?>
                                </label>
                            </td>
                            <td>
                                <?= implode(', ', unserialize($relation['attributes'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($available_relations)): ?>
                        <tr>
                            <td colspan="3">Не са намерени записи на релации</td>
                        </tr>
                    <?php endif; ?>
                </table>
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="select_action">Изберете действие</label><br>
                        <select class="form-control custom-select" id="select_action" name="action">
                            <option value="none">Избор</option>
                            <option value="delete">Изтриване</option>
                        </select>
                        <input class="btn btn-primary" name="submit_relation_action" type="submit" value="Изпълни">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>