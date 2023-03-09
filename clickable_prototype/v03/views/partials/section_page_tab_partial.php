<?php
    /** DOCU: Temp fix for CPT issues when editing module tab details */
    $base_url = explode("views", $_SERVER["HTTP_REFERER"])[0];
?>
<?php foreach($module_tabs_json as $module_tab) { ?>
    <div class="section_page_tab show" id="tab_<?= $module_tab["id"] ?>">
        <form action="<?= $base_url ?>processes/manage_documentation.php" class="update_module_tab_form" method="POST">
            <input type="hidden" name="action" value="update_module_tab">
            <input type="hidden" name="module_id" value="<?= $module_tab["module_id"] ?>" class="module_id">
            <input type="hidden" name="tab_id" value="<?= $module_tab["id"] ?>" class="tab_id">
            <input type="text" class="tab_title" value="<?= $module_tab["title"] ?>">
            <textarea id="tab_content_<?= $module_tab["id"] ?>" class="tab_content"><?= $module_tab["content"] ?></textarea>
            <div class="tab_footer">
                <input type="hidden" name="is_comments_allowed" value="false">
                <label for="allow_comments_tab_<?= $module_tab["id"] ?>" class="checkbox_label">
                    <input type="checkbox" class="is_comments_allowed" id="allow_comments_tab_<?= $module_tab["id"] ?>" name="is_comments_allowed" <?= ($module_tab["is_comments_allowed"]) ? "checked='checked'" : "" ?>>
                    <div class="checkbox_marker"></div>
                    <span class="checkbox_text">Allow Comments</span>
                </label>
                <div class="row_placeholder"></div>
                <div class="saving_indicator">Saving...</div>
            </div>
        </form>
    </div>
<?php } ?>