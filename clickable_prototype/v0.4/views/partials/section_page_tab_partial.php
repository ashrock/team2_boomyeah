<?php
    /** DOCU: Temp fix for CPT issues when editing module tab details */
    $view_url = strpos($_SERVER["REQUEST_URI"], "views") ? $_SERVER["REQUEST_URI"] : $_SERVER["HTTP_REFERER"];
    $base_url = explode("views", $view_url)[0];
?>
<?php foreach($module_tabs_json as $module_tab) { ?>
    <div class="section_page_tab" id="tab_<?= $module_tab["id"] ?>">
        <form action="<?= $base_url ?>processes/manage_documentation.php" class="update_module_tab_form" method="POST">
            <input type="hidden" name="action" value="update_module_tab">
            <input type="hidden" name="module_id" value="<?= $module_tab["module_id"] ?>" class="module_id">
            <input type="hidden" name="tab_id" value="<?= $module_tab["id"] ?>" class="tab_id">
            <input type="text" class="tab_title" name="module_title" value="<?= $module_tab["title"] ?>">
            <textarea id="tab_content_<?= $module_tab["id"] ?>" name="module_content" class="tab_content"><?= $module_tab["content"] ?></textarea>
            <div class="tab_footer">
                <input type="hidden" name="is_comments_allowed" value="">
                <label for="allow_comments_tab_<?= $module_tab["id"] ?>" class="checkbox_label">
                    <input type="checkbox" class="is_comments_allowed" id="allow_comments_tab_<?= $module_tab["id"] ?>" <?= ($module_tab["is_comments_allowed"] === 1) ? "checked='checked'" : "" ?>>
                    <div class="checkbox_marker"></div>
                    <span class="checkbox_text">Allow Comments</span>
                </label>
                <div class="row_placeholder"></div>
                <div class="saving_indicator">Saving...</div>
            </div>
        </form>
    </div>
<?php } ?>