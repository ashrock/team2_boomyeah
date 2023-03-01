let valid_email = true;
let invited_emails = [];
let invite_instance = null;
let role_instance   = null;

document.addEventListener("DOMContentLoaded", async () => {
    
    initializeMaterializeTooltip();
    appearEmptySection();
    initializeMaterializeDropdown();

    document.addEventListener("click", (event) => {
        let element = event.target.closest(".add_invite_result");
        
        if(element){
            addSearchEmailResult(element);
        }
    });

    ux("body")
        .on("click", "#add_invite_btn", addPeopleWithAccess)
        .on("click", "#remove_invited_user_confirm",submitRemoveInvitedUser)
        .on("change", ".invited_user_role",setRoleChangeAction)
        .on("submit", "#add_collaborators_form", onSubmitAddCollaboratorsForm)

    /* run functions from invite_modal.js */
    initializeCollaboratorChipsInstance();
    // initRoleDropdown();
    initSelect();

    ux("#invite_collaborator_btn").trigger("click");
})

function initSelect(dropdown_selector = "select"){
    M.FormSelect.init(document.querySelectorAll(dropdown_selector));
}

function initializeCollaboratorChipsInstance(){
    let collaborator_chips = document.querySelector(".collaborator_chips");
    M.Chips.init(collaborator_chips, {
        placeholder: "Enter email address",
        secondaryPlaceholder: "Enter email address",
        onChipAdd: (element, email) => {
            let collaborator_email = email.innerText.split("close")[0];
            
            if(validateEmail(collaborator_email)){
                addEmail(collaborator_email);
            } else {
                email.remove();
                setTimeout(() => {
                    ux(".collaborator_email_address").val(collaborator_email);
                });
            }
        }
    });
}

function initRoleDropdown(){
    var role_instance = M.Dropdown.init(document.querySelectorAll('.role'));
}

function addEmail(email){
    invited_emails.push(email);
}

function getNewInvitedUserId(event){
    let invited_users = document.querySelectorAll(".invited_user");
    let largest_id = 1;

    invited_users.forEach(invited_user => {
        if(invited_user.id){
            let invited_user_id = parseInt(invited_user.id.split("_")[2]);
            
            if(invited_user_id > largest_id){
                largest_id = invited_user_id + 1;
            }
        }
    });

    return largest_id;
}

function addPeopleWithAccess(event){
    event.stopPropagation();
    
    let new_invited_user_id = getNewInvitedUserId();
    if(invited_emails.length > 0){
        let add_collaborators_form = ux("#add_collaborators_form");
        add_collaborators_form.find(".collaborator_emails").val(invited_emails.join(","));
        add_collaborators_form.trigger("submit");
    }

    invited_emails = [];
    initializeCollaboratorChipsInstance();
}

function onSubmitAddCollaboratorsForm(event){
    event.preventDefault();
    let post_form = ux(event.target);

    ux().post(post_form.attr("action"), post_form.serialize(), async (response_data) => {
        if(response_data.status){
            await ux("#invited_users_wrapper").append(response_data.result.html);
            
            ux("#invited_users_wrapper").findAll(".added_collaborator").forEach(dropdown_element => {
                M.FormSelect.init(dropdown_element);
                ux(dropdown_element).removeClass("added_collaborator");
            });
        } else {

        }
    }, "json");

    return false;
}

function setRoleChangeAction(event){
    const selected_action = event.target.value;
    const invited_user_id = event.target.dataset.invited_user_id;

    if(selected_action === "remove"){
        let remove_invited_user_modal = document.querySelector("#confirm_to_remove_invited_user");
        var instance = M.Modal.getInstance(remove_invited_user_modal);
        instance.open();

        ux("#invited_user_id").val(invited_user_id);
    }
    else{
        // for changing role to viewer/editor in the backend
    }
}

function submitRemoveInvitedUser(event){
    const invited_user_id      = ux("#invited_user_id").val();
    const invited_user_element = ux(`#invited_user_${invited_user_id}`).self();

    invited_user_element.className += " animate__animated animate__fadeOut";
        
    invited_user_element.addEventListener("animationend", () => {
        invited_user_element.remove();
    }, false);
}
