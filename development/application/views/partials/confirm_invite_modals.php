<div id="confirmation_modal_remove_invited_user">
    <div id="confirm_to_remove_invited_user" class="modal">
        <div class="modal-content">
            <h4>Confirmation</h4>
            <p>Are you sure you want to remove access for this user?</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect btn-flat no_btn">No</a>
            <a href="#!" id="remove_invited_user_confirm" class="modal-close waves-effect btn-flat yes_btn">Yes</a>
        </div>
    </div>
</div>
<form id="remove_invited_user_form" action="<?= BASE_FILE_URL ?>processes/manage_documentation.php" method="POST" hidden>
    <input type="hidden" name="action" value="remove_collaborator">
    <input type="hidden" name="invited_user_id" class="invited_user_id">
</form>
<form id="update_invited_user_form" action="/collaborators/update" method="POST" hidden>
    <input type="hidden" name="action" value="update_collaborator">
    <input type="hidden" name="invited_user_id" class="invited_user_id">
    <input type="hidden" name="collaborator_id" class="collaborator_id">
    <input type="hidden" name="update_type" class="update_type" value="collaborator_level_id">
    <input type="hidden" name="update_value" class="update_value" value="">
    <input type="hidden" name="email" class="email" value="">
</form>