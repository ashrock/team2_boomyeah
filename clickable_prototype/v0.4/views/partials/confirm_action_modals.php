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
            <form action="<?= BASE_FILE_URL ?>processes/manage_documentation.php" method="POST" id="remove_tab_form">
                <input type="hidden" name="action" value="remove_module_tab">
                <input type="hidden" name="module_id" value="" class="module_id">
                <input type="hidden" name="tab_id" value="" class="tab_id">
                <button type="button" class="modal-close waves-effect btn-flat no_btn">No</button>
                <button type="submit" class="modal-close waves-effect btn-flat yes_btn">Yes</button>
            </form>
        </div>
    </div>
</div>
<div id="confirm_remove_uploaded_file" class="confirm_action_modal">
    <div id="confirm_remove_uploaded_file_modal" class="modal">
        <div class="modal-content">
            <h4>Confirmation</h4>
            <p class="remove_file_question_text"></p>
        </div>
        <div class="modal-footer">
            <form action="<?= BASE_FILE_URL ?>processes/manage_documentation.php" method="POST" id="remove_uploaded_file_form">
                <input type="hidden" name="action" value="remove_uploaded_file">
                <input type="hidden" name="file_id" value="" class="file_id">
                <input type="hidden" name="file_name" value="" class="file_name">
                <button type="button" class="modal-close waves-effect btn-flat no_btn">No</button>
                <button type="submit" class="modal-close waves-effect btn-flat yes_btn">Yes</button>
            </form>
        </div>
    </div>
</div>
<div id="error_uploaded_file" class="confirm_action_modal">
    <div id="error_uploaded_file_modal" class="modal">
        <div class="modal-content">
            <h4 class="error_title"></h4>
            <p class="error_file_name"></p>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-close waves-effect btn-flat no_btn">Close</button>
        </div>
    </div>
</div>