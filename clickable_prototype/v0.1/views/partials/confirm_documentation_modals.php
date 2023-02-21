
<div id="confirmation_modal">
    <div id="confirm_to_public" class="modal">
        <div class="modal-content">
            <h4>Confirmation</h4>
            <p>Are you sure you want to change this documentation to Public?</p>
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
            <p>Are you sure you want to change this documentation to Private?</p>
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
            <p>Are you sure you want to move this documentation to Archive?</p>
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
            <p>Are you sure you want to remove this documentation?</p>
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
    <input type="hidden" id="change_privacy_doc_id" class="document_id" name="document_id">
    <input type="hidden" id="change_privacy_doc_privacy" name="document_privacy">
    <input type="hidden" name="action" value="change_documentation_privacy">
</form>
<form id="remove_archive_form" action="<?= BASE_FILE_URL ?>processes/manage_documentation.php" method="POST" hidden>
    <input type="hidden" id="documentation_action" name="documentation_action">
    <input type="hidden" id="remove_archive_id" name="document_id">
</form>
<form action="remove_invited_user_form" action="<?= BASE_FILE_URL ?>processes/manage_documentation.php" method="POST" hidden>
    <input type="hidden" id="invited_user_id" name="invited_user_id">
</form>