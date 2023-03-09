<?php foreach($module_tabs_json as $module_tab) { ?>
    <div class="section_page_tab show" id="tab_<?= $module_tab["id"] ?>">
        <input type="text" class="tab_title" value="<?= $module_tab["title"] ?>">
        <textarea id="tab_content_<?= $module_tab["id"] ?>" class="tab_content"><?= $module_tab["content"] ?></textarea>
        <div class="tab_footer">
            <label for="allow_comments_tab_<?= $module_tab["id"] ?>" class="checkbox_label">
                <input type="checkbox" id="allow_comments_tab_<?= $module_tab["id"] ?>" name="allow_comments" <?= ($module_tab["is_comments_allowed"]) ? "checked='checked'" : "" ?>>
                <div class="checkbox_marker"></div>
                <span class="checkbox_text">Allow Comments</span>
            </label>
            <div class="row_placeholder"></div>
            <div class="saving_indicator">Saving...</div>
        </div>
    </div>
<?php } ?>