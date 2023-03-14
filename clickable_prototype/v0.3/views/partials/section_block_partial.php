<div id="section_<?= $id ?>" class="section_block">
    <input type="hidden" name="section_id" value="<?= $id ?>" class="section_id">
    <input type="hidden" name="original_section_title" value="<?= $title ?>">
    <div class="drag_handle"></div>
    <div class="section_title autoheight"><?= $title ?></div>
    <ul id="section_more_actions_<?= $id ?>" class="more_action_list">
        <li class="edit_title_btn"><a href="#!" data-section_id="<?= $id ?>" class="section_block_icon edit_title_icon"></a></li>
        <li><a href="#!" data-section_id="<?= $id ?>" class="section_block_icon duplicate_icon"></a></li>
        <li><a href="#confirm_to_remove" class="section_block_icon remove_icon modal-trigger remove_btn" data-section_id="<?= $id ?>" data-section_title="<?= $title ?>"  data-documentation_action="remove"></a></li>
    </ul>
</div>