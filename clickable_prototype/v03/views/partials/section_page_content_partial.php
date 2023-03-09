<?php foreach($modules as $module) {  ?>
    <div class="section_page_content" id="module_<?= $module["id"] ?>">
        <ul class="section_page_tabs">
            <?php foreach($module["module_tabs_json"] as $module_tab){ ?>
                <li class="page_tab_item active" data-tab_id="tab_<?= $module_tab["id"] ?>">
                    <a href="#tab_<?= $module_tab["id"] ?>"><?= $module_tab["title"] ?></a>
                    <button type="button" class="remove_tab_btn">&times;</button>
                </li>
            <?php } ?>
            <li class="add_page_tab">
                <button class="add_page_btn" type="button">+</button>
            </li>
        </ul>
        <?php
            /** DOCU: $views_path is specified for this file in order for the partial be loaded from FE and BE side */
            load_view( $views_path ."partials/section_page_tab_partial.php", array("module_tabs_json" => $module["module_tabs_json"]));
        ?>
    </div>
<?php } ?>