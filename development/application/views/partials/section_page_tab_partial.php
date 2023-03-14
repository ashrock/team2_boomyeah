<?php $module["module_tabs_json"] = json_decode($module["module_tabs_json"]); ?>

<?php foreach($module_tabs as $module_index => $module_tab){
    $tab = $module["module_tabs_json"]->$module_tab;
?>
    <div class="section_page_tab" id="tab_<?= $tab->id ?>">
        <form action="processes/manage_documentation.php" class="update_module_tab_form" method="POST">
            <input type="hidden" name="action" value="update_module_tab">
            <input type="hidden" name="module_id" value="<?= $module['module_id'] ?>" class="module_id">
            <input type="hidden" name="tab_id" value="<?= $tab->id ?>" class="tab_id">
            <input type="text" class="tab_title" name="module_title" value="<?= $tab->title ?>">
            <textarea id="tab_content_<?= $tab->id ?>" name="module_content" class="tab_content"><?= $tab->content ?></textarea>
            <div class="tab_footer">
                <input type="hidden" name="is_comments_allowed" value="false">
                <label for="allow_comments_tab_<?= $tab->id ?>" class="checkbox_label">
                    <input type="checkbox" class="is_comments_allowed" id="allow_comments_tab_<?= $tab->id ?>" name="is_comments_allowed" <?= ($tab->is_comments_allowed) ? "checked='checked'" : "" ?>>
                    <div class="checkbox_marker"></div>
                    <span class="checkbox_text">Allow Comments</span>
                </label>
                <div class="row_placeholder"></div>
                <div class="saving_indicator">Saving...</div>
            </div>
        </form>
    </div>
<?php } ?>