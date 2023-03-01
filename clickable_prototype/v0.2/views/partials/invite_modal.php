<div id="invite_modal_container">
    <div class="invite_wrapper">
        <div id="invite_collaborator_modal" class="invite_modal modal">
            <div class="modal-content">
                <h2>Invite Collaborators</h2>
                <p>Collaborators will be able to access your private documentations.</p>
            </div>
            <form action="#" method="POST" class="invite_collab" id="invite_form">
                <div class="row">
                    <div class="collaborator_row input-field col s6">
                        <div class="collaborator_chips">
                            <input id="email_address" type="text" class="validate email_address collaborator_email_address"/>
                            <div class="row_placeholder"></div>
                            <a id="add_invite_btn" class="users add_collaborator_btn" href="#" data-target="add_invite"></a>
                        </div>
                        <ul id="add_invite" class="dropdown-content"></ul>
                    </div>
                </div>
                <div id="with_access_div">
                    <h2 class="access">People with access</h2>
                    <div id="invited_users_wrapper" class="invited_users_wrapper">
                        <div class="invited_user">
                            <div class="invited_user_info">
                                <p>kbtonel1@village88.com</p>
                            </div>
                            <p>Owner</p>    
                        </div>
                        <div id="invited_user_1" class="invited_user">
                            <div class="invited_user_info">
                                <p>mchoi@village88.com</p>
                            </div>
                            <select name="invited_user_role" class="invited_user_role" data-invited_user_id="1">
                                <option value="viewer" selected>Viewer</option>
                                <option value="editor">Editor</option>
                                <option value="remove">Remove</option>
                            </select>
                        </div>
                        <div id="invited_user_2" class="invited_user">
                            <div class="invited_user_info">
                                <p>jgurtiza@village88.com</p>
                            </div>
                            <select name="invited_user_role" class="invited_user_role" data-invited_user_id="2">
                                <option value="viewer">Viewer</option>
                                <option value="editor" selected>Editor</option>
                                <option value="remove">Remove</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="empty_search_wrapper" hidden>
                    <div class="no_result">
                        <p id="invite_result_msg"></p>
                        <img src="../../assets/images/empty.svg" alt="empty">
                    </div>
                </div>
            </form>
            <div class="modal-footer cta_done">
                <a href="#!" class="modal-close waves-effect btn-flat">Done</a>
            </div>
        </div>
    </div>
</div>
<form action="<?= BASE_FILE_URL ?>processes/manage_documentation.php" id="add_collaborators_form" method="POST">
    <input type="hidden" name="action" value="add_collaborators">
    <input type="hidden" name="collaborator_emails" class="collaborator_emails">
</form>
<form action="<?= BASE_FILE_URL ?>processes/manage_documentation.php" id="get_collaborators_form" method="POST">
    <input type="hidden" name="action" value="get_collaborators">
    <input type="hidden" name="document_id" class="document_id">
</form>