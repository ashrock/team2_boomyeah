
<div id="confirmation_modal">
    <div id="confirm_to_public" class="modal confirmation_modal">
        <div class="modal-content">
            <div class="confirmation_modal_icon public_modal_icon"></div>
            <h4>Are you sure?</h4>
            <p>Setting “<span class="documentation_title"></span>” to <b>Public</b> will allow all users to view your documentation.</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect btn-flat no_btn cancel_btn">Cancel</a>
            <a href="#!" class="modal-close waves-effect btn-flat yes_btn change_privacy_yes_btn proceed_btn">Proceed</a>
        </div>
    </div>
</div>
<div id="confirmation_modal_private">
    <div id="confirm_to_private" class="modal confirmation_modal">
        <div class="modal-content">
            <div class="confirmation_modal_icon private_modal_icon"></div>
            <h4>Are you sure?</h4>
            <p>Setting “<span class="documentation_title"></span>” to <b>Private</b> will only allow collaborators you select to view your documentation.</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect btn-flat no_btn cancel_btn">Cancel</a>
            <a href="#!" class="modal-close waves-effect btn-flat yes_btn change_privacy_yes_btn proceed_btn">Proceed</a>
        </div>
    </div>
</div>
<div id="confirmation_modal_archive">
    <div id="confirm_to_archive" class="modal confirmation_modal">
        <div class="modal-content">
            <div class="confirmation_modal_icon archive_modal_icon"></div>
            <h4>Are you sure?</h4>
            <p>Archiving `<span class="documentation_title"></span>` will hide this to all users. You can unarchive this later</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect btn-flat no_btn cancel_btn">Cancel</a>
            <a href="#!" id="archive_confirm" class="modal-close waves-effect btn-flat yes_btn proceed_btn">Proceed</a>
        </div>
    </div>
</div>
<div id="confirmation_modal_archive">
    <div id="confirm_to_duplicate_doc" class="modal confirmation_modal">
        <div class="modal-content">
            <div class="confirmation_modal_icon duplicate_modal_icon"></div>
            <h4>Are you sure?</h4>
            <p>Doing this will duplicate `<span class="documentation_title"></span>` and all of its sections and modules.</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect btn-flat no_btn cancel_btn">Cancel</a>
            <a href="#!" id="archive_confirm" class="modal-close waves-effect btn-flat yes_btn proceed_btn">Proceed</a>
        </div>
    </div>
</div>
<div id="confirmation_modal_archive">
    <div id="confirm_to_duplicate_section" class="modal confirmation_modal">
        <div class="modal-content">
            <div class="confirmation_modal_icon duplicate_modal_icon"></div>
            <h4>Are you sure?</h4>
            <p>Doing this will duplicate `<span id="duplicate_section_title"></span>` and all of its modules and tabs.</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect btn-flat no_btn cancel_btn">Cancel</a>
            <a href="#!" id="archive_confirm" class="modal-close waves-effect btn-flat yes_btn proceed_btn">Proceed</a>
        </div>
    </div>
</div>
<div id="confirmation_modal_remove">
    <div id="confirm_to_remove" class="modal confirmation_modal">
        <div class="modal-content">
            <div class="confirmation_modal_icon"></div>
            <h4>Are you sure?</h4>
            <p>Removing "<span id="section_title_to_remove" class="documentation_title"></span>" will also remove all of its content. This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect btn-flat no_btn">Cancel</a>
            <a href="#!" id="remove_confirm" class="modal-close waves-effect btn-flat yes_btn">Yes, Remove</a>
        </div>
    </div>
</div>
<div id="confirmation_modal_remove_invited_user">
    <div id="confirm_to_remove_invited_user" class="modal confirmation_modal">
        <div class="modal-content">
            <div class="confirmation_modal_icon"></div>
            <h4>Are you sure?</h4>
            <p>Do you really want to remove access for this user? This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect btn-flat no_btn">Cancel</a>
            <a href="#!" id="remove_invited_user_confirm" class="modal-close waves-effect btn-flat yes_btn">Yes, Remove</a>
        </div>
    </div>
</div>
<div id="confirmation_modal_remove">
    <div id="confirm_to_remove" class="modal confirmation_modal">
        <div class="modal-content">
            <div class="confirmation_modal_icon"></div>
            <h4>Are you sure?</h4>
            <p>Removing `<span class="section_title_to_remove"></span>` will also remove all the modules and tabs in the section. This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect btn-flat no_btn">Cancel</a>
            <a href="#!" id="remove_confirm" class="modal-close waves-effect btn-flat yes_btn">Yes, Remove</a>
        </div>
    </div>
</div>
<form id="change_document_privacy_form" action="/docs/update" method="POST" hidden>
    <input type="hidden" name="documentation_id" id="documentation_id" value="">
    <input type="hidden" name="update_type" value="is_private">
    <input type="hidden" name="update_value" id="update_value" value="">
</form>
<form id="archive_form" action="/docs/update" method="POST" hidden>
    <input type="hidden" name="documentation_id" id="documentation_id" value="">
    <input type="hidden" name="action" value="update_documentation">
    <input type="hidden" name="update_type" value="is_archived">
    <input type="hidden" name="update_value" id="update_value" value="">
</form>