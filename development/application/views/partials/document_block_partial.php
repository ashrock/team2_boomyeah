<?php
    for($documentations_index = 0; $documentations_index < count($all_documentations); $documentations_index++){
        $documentation = $all_documentations[$documentations_index];

        if($_SESSION["user_level_id"] == USER_LEVEL["ADMIN"]) { ?>
            <div id="document_<?= $documentation["id"] ?>" class="document_block" tabindex="3">
                <form action="/docs/update" method="POST" class="document_details edit_title_form" autocomplete="off">
                    <input type="text" name="update_value" value="<?= htmlspecialchars($documentation["title"]) ?>" class="document_title" readonly="" autocomplete="nope">
                    <input type="hidden" name="action" value="update_documentation">
                    <input type="hidden" name="update_type" value="title">
                    <input type="hidden" name="documentation_id" value="<?= $documentation["id"] ?>">
                    <input type="hidden" name="original_value" value="<?= $documentation["title"] ?>">
                    <p class="documentation_owner">by <?= $documentation["documentation_owner"] ?></p>
                    <?php if($documentation["is_private"]){ ?>
                    <?php } ?>
                </form>
                <div class="document_controls">
                    <?php if($documentation["is_private"]){ ?>
                        <button type="button" class="invite_collaborators_btn <?= ($documentation["is_archived"]) ? 'archived_disabled' : '' ?> tooltipped" data-tooltip="<?= $documentation["cache_collaborators_count"] + 1 ?> Collaborators" data-document_id="<?= $documentation["id"] ?>" tabindex="3"><?= $documentation["cache_collaborators_count"] + 1 ?></button>
                        <button class="access_btn modal-trigger <?= ($documentation["is_archived"]) ? 'archived_disabled' : '' ?> set_privacy_btn tooltipped" data-tooltip="Set to Public" href="#confirm_to_public" data-document_id="<?= $documentation["id"] ?>" data-document_privacy="private" tabindex="3"></button>
                    <?php } ?>
                    <button class="more_action_btn dropdown-trigger" data-target="document_more_actions_<?= $documentation["id"] ?>" tabindex="3">⁝</button>
                    <!-- Dropdown Structure -->
                    <ul id="document_more_actions_<?= $documentation["id"] ?>" class="dropdown-content more_action_list_private more_action_list_public">
                        <?php if(!$documentation["is_archived"]){ ?>
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon" tabindex="3">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#" class="duplicate_icon" data-document_id="<?= $documentation["id"] ?>" tabindex="3">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_archive" class="archive_icon modal-trigger archive_btn" data-document_id="<?= $documentation["id"] ?>" data-documentation_action="archive" tabindex="3">Archive</a></li>
                            <?php if($documentation["is_private"]){ ?>
                                <li class="divider" tabindex="-1"></li>
                                <li><a href="#" class="invite_icon invite_collaborators_btn" data-document_id="<?= $documentation["id"] ?>" tabindex="3">Invite</a></li>
                            <?php } ?>
                            <li class="divider" tabindex="-1"></li>
                            <?php if($documentation["is_private"]){ ?>
                                <li><a href="#confirm_to_public" class="set_to_public_icon modal-trigger set_privacy_btn" data-document_id="<?= $documentation["id"] ?>" data-document_privacy="private" tabindex="3">Set to Public</a></li>
                            <?php } else{ ?>
                                <li><a href="#confirm_to_private" class="set_to_private_icon modal-trigger" data-document_id="<?= $documentation["id"] ?>" data-document_privacy="public" tabindex="3">Set to Private</a></li>
                            <?php } ?>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="<?= $documentation["id"] ?>" data-documentation_action="remove" data-is_archived="0" tabindex="3">Remove</a></li>
                        <?php }else{ ?>
                            <li><a href="#confirm_to_archive" class="archive_icon modal-trigger archive_btn" data-document_id="<?= $documentation["id"] ?>" data-documentation_action="unarchive" tabindex="3">Unarchive</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="<?= $documentation["id"] ?>" data-documentation_action="remove" data-is_archived="1" tabindex="3">Remove</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
<?php   }
        else { ?>
            <div id="document_<?= $documentation["id"] ?>" class="document_block mobile_block">
                <div class="document_details">
                    <h2><?= $documentation['title'] ?></h2>
                    <p class="documentation_owner">by <?= $documentation["documentation_owner"] ?></p>
                    </div>
                <?php if($documentation['is_private']){ ?>
                    <button class="invite_collaborators_btn"><?= $documentation['cache_collaborators_count'] + 1 ?></button> 
                    <div class="document_controls"><button class="access_btn"></button></div>
                <?php  } ?>
            </div>
<?php   }
    }
?>