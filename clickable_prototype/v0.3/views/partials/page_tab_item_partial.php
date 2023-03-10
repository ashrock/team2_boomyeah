<?php foreach($module_tabs_json as $module_index => $module_tab){ ?>
    <li class="page_tab_item <?= ($module_index === 0) ? 'active' : '' ?> module_tab_<?= $module_tab["id"] ?>" data-tab_id="tab_<?= $module_tab["id"] ?>" data-module_id="<?= $module_tab["module_id"] ?>">
        <a href="#tab_<?= $module_tab["id"] ?>"><?= $module_tab["title"] ?></a>
        <button type="button" class="remove_tab_btn">&times;</button>
    </li>
<?php } ?>