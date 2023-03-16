<div id="confirm_remove_comment" class="confirm_action_modal">
    <div id="confirm_remove_comment_modal" class="modal">
        <div class="modal-content">
            <h4>Confirmation</h4>
            <p>Are you sure you want to remove this comment?</p>
        </div>
        <div class="modal-footer">
            <form action="<?= BASE_FILE_URL ?>processes/manage_documentation.php" method="POST" id="remove_comment_form">
                <input type="hidden" name="action" value="remove_comment" class="action">
                <input type="hidden" name="comment_id" value="" class="comment_id">
                <input type="hidden" name="post_id" value="" class="post_id">
                <button type="button" class="modal-close waves-effect btn-flat no_btn">No</button>
                <button type="submit" class="modal-close waves-effect btn-flat yes_btn">Yes</button>
            </form>
        </div>
    </div>
</div>
<div id="confirm_remove_tab" class="confirm_action_modal">
    <div id="confirm_remove_tab_modal" class="modal">
        <div class="modal-content">
            <h4>Confirmation</h4>
            <p>Are you sure you want to remove `<span class="tab_title"></span>` tab?</p>
        </div>
        <div class="modal-footer">
            <form action="/modules/remove_tab" method="POST" id="remove_tab_form">
                <input type="hidden" name="action" value="remove_module_tab">
                <input type="hidden" name="module_id" value="" class="module_id">
                <input type="hidden" name="tab_id" value="" class="tab_id">
                <button type="button" class="modal-close waves-effect btn-flat no_btn">No</button>
                <button type="submit" class="modal-close waves-effect btn-flat yes_btn">Yes</button>
            </form>
        </div>
    </div>
</div>