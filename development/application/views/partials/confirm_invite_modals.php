<form id="remove_invited_user_form" action="/collaborators/remove" method="POST" hidden>
    <input type="hidden" name="action" value="remove_collaborator">
    <input type="hidden" name="documentation_id" class="documentation_id">
    <input type="hidden" name="invited_user_id" class="invited_user_id">
    <input type="hidden" name="collaborator_id" class="collaborator_id">
</form>
<form id="update_invited_user_form" action="/collaborators/update" method="POST" hidden>
    <input type="hidden" name="action" value="update_collaborator">
    <input type="hidden" name="invited_user_id" class="invited_user_id">
    <input type="hidden" name="collaborator_id" class="collaborator_id">
    <input type="hidden" name="update_type" class="update_type" value="collaborator_level_id">
    <input type="hidden" name="update_value" class="update_value" value="">
    <input type="hidden" name="email" class="email" value="">
</form>