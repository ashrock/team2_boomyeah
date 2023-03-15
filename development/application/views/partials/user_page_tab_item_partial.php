<?php $module["module_tabs_json"] = json_decode($module["module_tabs_json"]); ?>

<?php foreach($module_tabs as $module_index => $module_tab){
    $tab = $module["module_tabs_json"]->$module_tab;
?>
    <li class="page_tab_item <?= ($module_index === 0) ? 'active' : '' ?> module_tab_<?= $tab->id ?>" data-tab_id="tab_<?= $tab->id ?>" data-module_id="<?= $tab->module_id ?>">
        <a href="#tab_<?= $tab->id ?>"><?= $tab->title ?></a>
    </li>
<?php } ?>