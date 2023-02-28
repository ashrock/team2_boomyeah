<div id="section_<?= $id ?>" class="section_block">
    <div class="section_details">
        <input type="text" name="section_title" value="<?= $title ?>" id="" class="section_title" readonly="">
    </div>
    <div class="section_controls">
        <button class="more_action_btn dropdown-trigger" data-target="section_more_actions_<?= $id ?>">⁝</button>
        <!-- Dropdown Structure -->
        <ul id="section_more_actions_<?= $id ?>" class="dropdown-content more_action_list">
            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="<?= $id ?>" data-documentation_action="remove">Remove</a></li>
        </ul>
    </div>
</div>