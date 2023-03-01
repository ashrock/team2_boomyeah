<div id="invited_user_<?= $id ?>" class="invited_user">
    <div class="invited_user_info">
        <p><?= $collaborator_email ?></p>
    </div>
    <select name="invited_user_role" class="added_collaborator invited_user_role" data-invited_user_id="<?= $id ?>">
        <option value="viewer">Viewer</option>
        <option value="editor" selected>Editor</option>
        <option value="remove">Remove</option>
    </select>
</div>