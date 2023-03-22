<?php foreach($modules as $module_key => $module) {  ?>
    <div class="section_page_content <?= $module_count == 1 ? 'active' : '' ?>" id="module_<?= $module["id"] ?>">
        <div class="mobile_module_header">Module <?= $module_count ?>/<?= $total_modules ?></div>
        <ul class="section_page_tabs">
            <?php
                /** DOCU: $views_path is specified for this file in order for the partial be loaded from FE and BE side */
                load_view( $views_path ."partials/user_page_tab_item_partial.php", array("module_tabs_json" => $module["module_tabs_json"]));
            ?>
        </ul>
        <?php
            /** DOCU: $views_path is specified for this file in order for the partial be loaded from FE and BE side */
            load_view( $views_path ."partials/user_section_page_tab_partial.php", array("module_tabs_json" => $module["module_tabs_json"]));
        ?>
    </div>
<?php } ?>