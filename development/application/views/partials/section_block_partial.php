<?php
    foreach($all_sections as $section){
        /* TODO: Make BE provide last_updated_at field */
        $section["last_updated_at"] = date("F jS, Y", time());
        ?>

        <div id="section_<?= $section['id'] ?>" class="section_block" tabindex="2">
            <?php if($_SESSION["user_level_id"] == USER_LEVEL["ADMIN"]) { ?>    
                <input type="hidden" name="section_id" value="<?= $section['id'] ?>" class="section_id">
                <input type="hidden" name="original_section_title" value="<?= $section['title'] ?>">
                <div class="drag_handle"></div>
                <div class="section_title autoheight"><?= $section['title'] ?></div>
                <ul id="section_more_actions_<?= $section['id'] ?>" class="more_action_list">
                    <li class="edit_title_btn"><a href="#" tabindex="2" data-section_id="<?= $section['id'] ?>" class="section_block_icon edit_title_icon tooltipped" data-tooltip="Edit Section Title"></a></li>
                    <li><a href="#" tabindex="2" data-section_id="<?= $section['id'] ?>" class="section_block_icon duplicate_icon tooltipped" data-tooltip="Duplicate Section"></a></li>
                    <li><a href="#confirm_to_remove" tabindex="2" class="section_block_icon remove_icon modal-trigger remove_btn tooltipped" data-tooltip="Remove Section" data-section_id="<?= $section['id'] ?>" data-section_title="<?= $section['title'] ?>"  data-documentation_action="remove"></a></li>
                </ul>
            <?php } else { ?> 
                <div class="section_details">
                    <div class="section_title" tabindex="2" data-tooltip="<?= $section["title"] ?>"><?= $section["title"] ?></div>
                    <div class="last_updated_at">Updated <?= $section['last_updated_at'] ?></div>
                </div>
            <?php }?>
        </div>
<?php 
    }   
?>