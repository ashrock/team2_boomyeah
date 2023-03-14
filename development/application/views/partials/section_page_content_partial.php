<?php foreach($modules as $module) {  ?>
    <div class="section_page_content" id="module_<?= $module['module_id'] ?>">
        <ul class="section_page_tabs">
            <?php
                /** DOCU: $views_path is specified for this file in order for the partial be loaded from FE and BE side */
                if($module["tab_ids_order"]){
                    $this->load->view("partials/page_tab_item_partial.php", array("module" => $module, "module_tabs" => explode(",",$module["tab_ids_order"])));
                }
            ?>
            <li class="add_page_tab">
                <button class="add_page_btn" type="button" data-module_id="<?= $module['module_id'] ?>">+</button>
            </li>
        </ul>
        <?php
            /** DOCU: $views_path is specified for this file in order for the partial be loaded from FE and BE side */
            if($module["tab_ids_order"]){
                $this->load->view("partials/section_page_tab_partial.php", array("module" => $module, "module_tabs" => explode(",",$module["tab_ids_order"])));
            }
        ?>
    </div>
<?php } ?>
