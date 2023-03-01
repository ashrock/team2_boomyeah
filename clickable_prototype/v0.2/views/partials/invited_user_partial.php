<div id="invited_user_<?= $id ?>" class="invited_user">
    <div class="invited_user_info">
        <p><?= $collaborator_email ?></p>
    </div>
    <?php if($is_owner){?>
        <p>Owner</p>
    <?php }else{?>
        <select name="invited_user_role" class="added_collaborator invited_user_role" data-invited_user_id="<?= $id ?>">
            <option value="viewer" <?= ((int) $collaborator_level_id === 1) ? "selected" : "" ?>>Viewer</option>
            <option value="editor" <?= ((int) $collaborator_level_id === 2) ? "selected" : "" ?>>Editor</option>
            <option value="remove">Remove</option>
        </select>
    <?php } ?>
</div>