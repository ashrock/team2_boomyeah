<?php
    for($collaborators_index = 0; $collaborators_index < count($collaborators); $collaborators_index++){
        $collaborator = $collaborators[$collaborators_index]; ?>

        <div id="invited_user_<?= $collaborator["id"] ?>" class="invited_user">
            <div class="invited_user_info">
                <p><?= $collaborator["email"] ?></p>
            </div>
            <select name="invited_user_role" class="added_collaborator invited_user_role" data-invited_user_id="<?= $collaborator["id"] ?>" data-collaborator_id="<?= $collaborator["collaborator_id"] ?>">
                <option value="viewer" <?= ((int) $collaborator["collaborator_level_id"] === 1) ? "selected" : "" ?>>Viewer</option>
                <option value="editor" <?= ((int) $collaborator["collaborator_level_id"] === 2) ? "selected" : "" ?>>Editor</option>
                <option value="remove">Remove</option>
            </select>
        </div>
<?php
    }
?>