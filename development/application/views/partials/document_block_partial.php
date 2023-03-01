<?php if($_SESSION["user_level_id"] == USER_LEVEL["ADMIN"]){ ?>
    <div id="document_<?= $id ?>" class="document_block">
        <form action="/docs/update" method="POST" class="document_details edit_title_form" autocomplete="off">
            <input type="text" name="update_value" value="<?= $title ?>" class="document_title" readonly="" autocomplete="nope">
            <input type="hidden" name="action" value="update_documentation">
            <input type="hidden" name="update_type" value="title">
            <input type="hidden" name="documentation_id" value="<?= $id ?>">
            <?php if($is_private){ ?>
                <button type="button" class="invite_collaborators_btn modal-trigger <?= ($is_archived) ? 'archived_disabled' : '' ?>" tabindex="-1">&nbsp;<?= $cache_collaborators_count ?></button>
            <?php } ?>
        </form>
        <div class="document_controls">
            <?php if($is_private){ ?>
                <button class="access_btn modal-trigger <?= ($is_archived) ? 'archived_disabled' : '' ?> set_privacy_btn" href="#confirm_to_public" data-document_id="<?= $id ?>" data-document_privacy="private"></button>
            <?php } ?>
            <button class="more_action_btn dropdown-trigger" data-target="document_more_actions_<?= $id ?>">⁝</button>
            <!-- Dropdown Structure -->
            <ul id="document_more_actions_<?= $id ?>" class="dropdown-content more_action_list_private more_action_list_public">
                <?php if(!$is_archived){ ?>
                    <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                    <li class="divider" tabindex="-1"></li>
                    <li><a href="#!" class="duplicate_icon" data-document_id="<?= $id ?>">Duplicate</a></li>
                    <li class="divider" tabindex="-1"></li>
                    <li><a href="#confirm_to_archive" class="archive_icon modal-trigger archive_btn" data-document_id="<?= $id ?>" data-documentation_action="archive">Archive</a></li>
                    <?php if($is_private){ ?>
                        <li class="divider" tabindex="-1"></li>
                        <li><a href="#modal1" class="invite_icon modal-trigger">Invite</a></li>
                    <?php } ?>
                    <li class="divider" tabindex="-1"></li>
                    <?php if($is_private){ ?>
                        <li><a href="#confirm_to_public" class="set_to_public_icon modal-trigger set_privacy_btn" data-document_id="<?= $id ?>" data-document_privacy="private">Set to Public</a></li>
                    <?php } else{ ?>
                        <li><a href="#confirm_to_private" class="set_to_private_icon modal-trigger set_privacy_btn" data-document_id="<?= $id ?>" data-document_privacy="public">Set to Private</a></li>
                    <?php } ?>
                    <li class="divider" tabindex="-1"></li>
                    <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="<?= $id ?>" data-documentation_action="remove" data-is_archived="0">Remove</a></li>
                <?php }else{ ?>
                    <li><a href="#confirm_to_archive" class="archive_icon modal-trigger archive_btn" data-document_id="<?= $id ?>" data-documentation_action="unarchive">Unarchive</a></li>
                    <li class="divider" tabindex="-1"></li>
                    <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="<?= $id ?>" data-documentation_action="remove" data-is_archived="1">Remove</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
<?php } else { ?>
    <div class="document_block mobile_block">
        <div class="document_details">
            <h2><?= $title ?></h2>
            <?php if($is_private){ ?>
                <button class="invite_collaborators_btn"><?= $cache_collaborators_count ?></button> 
            <?php  } ?>
        </div>
        <?php if($is_private){ ?>
            <div class="document_controls"><button class="access_btn"></button></div>
        <?php  } ?>
    </div>
<?php } ?>