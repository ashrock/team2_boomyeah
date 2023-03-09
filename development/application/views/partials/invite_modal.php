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
                    <div id="invited_users_wrapper" class="invited_users_wrapper"></div>
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
<form action="/collaborators/add" id="add_collaborators_form" method="POST">
    <input type="hidden" name="document_id" class="document_id">
    <input type="hidden" name="collaborator_emails" class="collaborator_emails">
    <input type="hidden" name="cache_collaborators_count" class="cache_collaborators_count">
</form>
<form action="/collaborators/get" id="get_collaborators_form" method="POST">
    <input type="hidden" name="action" value="get_collaborators">
    <input type="hidden" name="document_id" class="document_id">
</form>