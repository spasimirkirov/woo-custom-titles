<?php

if (isset($_POST['submit_create_link'])) {

    $requestApi = new \WooCustomTitles\Inc\Request();
    $input_category = intval($_POST['category_id']);
    $input_meta = array_filter($_POST['meta_name']);

    if ($input_category && $input_meta)
        $requestApi->action_create_relation($input_category, $input_meta);

    if ($input_category === 0)
        show_message('<div class="error notice notice-error is-dismissible"><p>Не сте посочили таксономия</p></div>');

    if (!$input_meta)
        show_message('<div class="error notice notice-error is-dismissible"><p>Не сте посочили мета атрибут</p></div>');
}

$available_attribute_relations = WooProductAttributes\Inc\Api::list_relations();
?>
<div class="container my-2">
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs p-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?= admin_url('admin.php?page=woo_custom_titles'); ?>">Релации</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active"
                       href="<?= admin_url('admin.php?page=woo_custom_titles_create'); ?>">Добави</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                       href="<?= admin_url('admin.php?page=woo_custom_titles_settings'); ?>">Настройки</a>
                </li>
            </ul>
            <div class="card-header bg-dark text-light">
                Създаване на релация меджу Продуктова категория и атрибути
            </div>
        </div>
    </div>
    <form action="" method="post">
        <div class="p-2">
            <div class="form-row">
                <div class="col-md-6 form-group">
                    <h6>Изобр на мета атрибути</h6>
                    <?php for ($i = 0; $i < 3; $i++): ?>
                        <div class="form-row mb-1">
                            <label>
                                <select class="form-control" id="select_meta" name="meta_name[]">
                                    <option value="">Избор</option>
                                    <?php foreach ($available_attribute_relations as $j => $relation): ?>
                                        <option disabled class="select-relation-meta"
                                                data-parent="<?= $relation['category_id'] ?>">
                                            <?php echo $relation['category_name'] ?>
                                        </option>
                                        <?php foreach (unserialize($relation['meta_value']) as $attribute): ?>
                                            <option class="select-relation-meta"
                                                    data-parent="<?= $relation['category_id'] ?>"
                                                    value="<?php echo $attribute; ?>">
                                                &#x21B3; <?php echo $attribute ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="select-relation-category">Избор на категория</label>
                    <select class="form-control" id="select-relation-category" name="category_id">
                        <option value="none">Избор</option>
                        <?php foreach ($available_attribute_relations as $relation): ?>
                            <option value="<?php echo $relation['category_id']; ?>"> &#x21B3;
                                <?php echo $relation['category_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-secondary">
                        За създаване на категории, вижте
                        <a href="<?= admin_url('edit-tags.php?taxonomy=product_cat&post_type=product') ?>">
                            Продукти->категории
                        </a>
                    </p>
                </div>
                <div class="form-group col">
                    <input class="btn btn-primary btn-sm" name="submit_create_link" type="submit" value="Свързване">
                </div>
            </div>
        </div>

    </form>
</div>