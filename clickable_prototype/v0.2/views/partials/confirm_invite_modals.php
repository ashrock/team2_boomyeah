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
<form action="remove_invited_user_form" action="#" method="POST" hidden>
    <input type="hidden" id="invited_user_id" name="invited_user_id">
</form>