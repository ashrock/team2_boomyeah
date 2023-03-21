<?php foreach($modules as $module_key => $module) {  ?>
    <div class="section_page_content" id="module_<?= $module["id"] ?>">
        <ul class="section_page_tabs">
            <?php
                /** DOCU: $views_path is specified for this file in order for the partial be loaded from FE and BE side */
                load_view( $views_path ."partials/page_tab_item_partial.php", array("module_tabs_json" => $module["module_tabs_json"]));
            ?>
            <li class="add_page_tab">
                <button class="add_page_btn" type="button" data-module_id="<?= $module["id"] ?>">+</button>
            </li>
        </ul>
        <?php
            /** DOCU: $views_path is specified for this file in order for the partial be loaded from FE and BE side */
            load_view( $views_path ."partials/section_page_tab_partial.php", array("module_tabs_json" => $module["module_tabs_json"]));
        ?>
    </div>
<?php } ?>