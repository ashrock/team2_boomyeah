<?php foreach($tab_ids_order as $tab_ids_index => $tab_id){
    $tab = $module_tabs_json->$tab_id;
?>
    <div class="section_page_tab" id="tab_<?= $tab->id ?>">
        <form action="/modules/update" class="update_module_tab_form" method="POST">
            <input type="hidden" name="section_id" value="<?= $section_id ?>" class="section_id">
            <input type="hidden" name="module_id" value="<?= $tab->module_id ?>" class="module_id">
            <input type="hidden" name="tab_id" value="<?= $tab->id ?>" class="tab_id">
            <div class="module_title">
                <input type="text" class="tab_title" name="module_title" value="<?= $tab->title ?>"  maxlength="45">
                <div class="character_count" data-length="<?= strlen($tab->title) ?>">/45</div>
            </div>
            <textarea id="tab_content_<?= $tab->id ?>" name="module_content" class="tab_content"><?= $tab->content ?></textarea>
            <div class="tab_footer">
                <input type="hidden" name="is_comments_allowed" value="<?= ($tab->is_comments_allowed == YES) ? YES : NO ?>">
                <label for="allow_comments_tab_<?= $tab->id ?>" class="checkbox_label">
                    <input type="checkbox" class="is_comments_allowed" id="allow_comments_tab_<?= $tab->id ?>" <?= ($tab->is_comments_allowed == 1) ? "checked='checked'" : "" ?>>
                    <div class="checkbox_marker"></div>
                    <span class="checkbox_text">Allow Comments</span>
                </label>
                <div class="row_placeholder"></div>
                <div class="saving_indicator">Saving...</div>
            </div>
        </form>
    </div>
<?php } ?>