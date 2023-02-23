
<div id="confirmation_modal">
    <div id="confirm_to_public" class="modal">
        <div class="modal-content">
            <h4>Confirmation</h4>
            <p>Are you sure you want to change `<span class="documentation_title"></span>` documentation to Public?</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect btn-flat no_btn">No</a>
            <a href="#!" class="modal-close waves-effect btn-flat yes_btn change_privacy_yes_btn">Yes</a>
        </div>
    </div>
</div>
<div id="confirmation_modal_private">
    <div id="confirm_to_private" class="modal">
        <div class="modal-content">
            <h4>Confirmation</h4>
            <p>Are you sure you want to change `<span class="documentation_title"></span>` documentation to Private?</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect btn-flat no_btn">No</a>
            <a href="#!" class="modal-close waves-effect btn-flat yes_btn change_privacy_yes_btn">Yes</a>
        </div>
    </div>
</div>
<div id="confirmation_modal_archive">
    <div id="confirm_to_archive" class="modal">
        <div class="modal-content">
            <h4>Confirmation</h4>
            <p>Are you sure you want to move `<span class="documentation_title"></span>` documentation to Archive?</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect btn-flat no_btn">No</a>
            <a href="#!" id="archive_confirm" class="modal-close waves-effect btn-flat yes_btn">Yes</a>
        </div>
    </div>
</div>
<div id="confirmation_modal_remove">
    <div id="confirm_to_remove" class="modal">
        <div class="modal-content">
            <h4>Confirmation</h4>
            <p>Are you sure you want to remove `<span class="documentation_title"></span>` documentation?</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect btn-flat no_btn">No</a>
            <a href="#!" id="remove_confirm" class="modal-close waves-effect btn-flat yes_btn">Yes</a>
        </div>
    </div>
</div>
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
<form id="change_document_privacy_form" action="<?= BASE_FILE_URL ?>processes/manage_documentation.php" method="POST" hidden>
    <input type="hidden" name="documentation_id" id="documentation_id" value="">
    <input type="hidden" name="action" value="update_documentation">
    <input type="hidden" name="update_type" value="is_private">
    <input type="hidden" name="update_value" id="update_value" value=""> 

</form>
<form id="archive_form" action="<?= BASE_FILE_URL ?>processes/manage_documentation.php" method="POST" hidden>
    <input type="hidden" name="documentation_id" id="documentation_id" value="">
    <input type="hidden" name="action" value="update_documentation">
    <input type="hidden" name="update_type" value="is_archived">
    <input type="hidden" name="update_value" id="update_value" value="">
</form>
<form action="remove_invited_user_form" action="<?= BASE_FILE_URL ?>processes/manage_documentation.php" method="POST" hidden>
    <input type="hidden" id="invited_user_id" name="invited_user_id">
</form>