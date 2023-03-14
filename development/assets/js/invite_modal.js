let valid_email = true;
let invited_emails = [];
let invite_instance = null;
let role_instance   = null;
const COLLABORATOR_LEVEL = {
    "viewer" : 1,
    "editor" : 2,
}
document.addEventListener("DOMContentLoaded", async () => {
    let modal = document.querySelectorAll('.invite_modal');
    M.Modal.init(modal);
    initializeMaterializeTooltip();
    initializeMaterializeDropdown();

    document.addEventListener("click", (event) => {
        let element = event.target.closest(".add_invite_result");
        
        if(element){
            addSearchEmailResult(element);
        }
    });

    ux("body")
        .on("click", "#add_invite_btn", addPeopleWithAccess)
        .on("click", "#remove_invited_user_confirm", confirmRemoveInvitedUser)
        .on("click", ".invite_collaborators_btn", function(event){
            event.stopImmediatePropagation();
            event.preventDefault();
            let document_id               = ux(event.target).data("document_id");
            let get_collaborators_form    = ux("#get_collaborators_form");
            let add_collaborators_form    = ux("#add_collaborators_form");

            get_collaborators_form.find(".document_id").val(document_id);
            add_collaborators_form.find(".document_id").val(document_id);
            get_collaborators_form.trigger("submit");
        })
        .on("change", ".invited_user_role", setRoleChangeAction)
        .on("submit", "#add_collaborators_form", onSubmitAddCollaboratorsForm)
        .on("submit", "#get_collaborators_form", onSubmitGetCollaboratorsForm)
        .on("submit", "#remove_invited_user_form", onSubmitRemoveInvitedUser)
        .on("submit", "#update_invited_user_form", onSubmitUpdateInvitedUser)

    /* run functions from invite_modal.js */
    initializeCollaboratorChipsInstance();
    // initRoleDropdown();
    initSelect();
});

function checkAddedCollaboratorEmails(){
    let add_invite_btn = ux("#add_invite_btn");
    let collaborator_count = ux(".collaborator_chips").findAll(".chip").length;
    add_invite_btn.conditionalClass("disabled", !collaborator_count);
}

function initSelect(dropdown_selector = "select"){
    M.FormSelect.init(document.querySelectorAll(dropdown_selector));
}

function initializeCollaboratorChipsInstance(){
    let collaborator_chips = document.querySelector(".collaborator_chips");
    M.Chips.init(collaborator_chips, {
        placeholder: "Email address",
        secondaryPlaceholder: "Type email address",
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
        },
        onChipDelete: () => {
            checkAddedCollaboratorEmails();
        }
    });

    checkAddedCollaboratorEmails();
}

function initRoleDropdown(){
    var role_instance = M.Dropdown.init(document.querySelectorAll('.role'));
}

function addEmail(email){
    invited_emails.push(email.trim());
    checkAddedCollaboratorEmails();
}


function addPeopleWithAccess(event){
    event.stopPropagation();
    
    if(invited_emails.length > 0){
        let add_collaborators_form = ux("#add_collaborators_form");
        add_collaborators_form.find(".collaborator_emails").val(invited_emails.join(","));
        add_collaborators_form.trigger("submit");
    }

    invited_emails = [];
    initializeCollaboratorChipsInstance();
}

function onSubmitGetCollaboratorsForm(event){
    event.preventDefault();
    let post_form = ux(event.target);

    ux().post(post_form.attr("action"), post_form.serialize(), async (response_data) => {
        if(response_data.status){
            await ux("#invited_users_wrapper").html(response_data.result.owner);
            await ux("#invited_users_wrapper").append(response_data.result.html);
            
            ux("#invited_users_wrapper").findAll(".added_collaborator").forEach(dropdown_element => {
                M.FormSelect.init(dropdown_element);
                ux(dropdown_element).removeClass("added_collaborator");
            });

            /** TODO: Get people with access content */
            let invite_modal = document.querySelector("#invite_collaborator_modal");
            M.Modal.getInstance(invite_modal).open();
            
            setTimeout(() => {
                initializeInviteEmailField();   
            }, 480);
        } else {

        }
    }, "json");

    return false;
}

function initializeInviteEmailField(){
    ux("body")
        .on("focus", ".collaborator_email_address", function(event){
            event.stopImmediatePropagation();
            ux(".collaborator_chips").addClass("focused");
        })
        .on("blur", ".collaborator_email_address", function(event){
            event.stopImmediatePropagation();
            ux(".collaborator_chips").removeClass("focused");
        });

}

function onSubmitAddCollaboratorsForm(event){
    event.preventDefault();
    let post_form = ux(event.target);

    ux().post(post_form.attr("action"), post_form.serialize(), async (response_data) => {
        checkAddedCollaboratorEmails();
        
        if(response_data.status){
            await ux("#invited_users_wrapper").append(response_data.result.html);

            let invite_collaborator_btn = document.getElementById("invite_collaborator_btn");
            let collaborator_count      = parseInt(response_data.result.cache_collaborators_count) + 1;

            if(invite_collaborator_btn){
                invite_collaborator_btn.innerHTML = (`${collaborator_count} Collaborators`);
            }
            
            ux("#invited_users_wrapper").findAll(".added_collaborator").forEach(dropdown_element => {
                M.FormSelect.init(dropdown_element);
                ux(dropdown_element).removeClass("added_collaborator");
            });
        } else {
            alert(response_data.error);
        }
    }, "json");

    return false;
}

function setRoleChangeAction(event){
    let invited_user = ux(event.target.closest(".invited_user"));
    let collaborator_email = invited_user.find(".invited_user_info").self().innerText;
    const selected_action = event.target.value;
    const invited_user_id = event.target.dataset.invited_user_id;
    const collaborator_id = event.target.dataset.collaborator_id;

    if(selected_action === "remove"){
        let remove_invited_user_modal = document.querySelector("#confirm_to_remove_invited_user");
        var instance = M.Modal.getInstance(remove_invited_user_modal);
        instance.open();

        ux("#remove_invited_user_form").find(".invited_user_id").val(invited_user_id);
        ux("#remove_invited_user_form").find(".collaborator_id").val(collaborator_id);
        ux("#remove_invited_user_form").find(".documentation_id").val(invited_user.data("documentation_id"));
    }
    else{
        // for changing role to viewer/editor in the backend
        let update_invited_user_form = ux("#update_invited_user_form");
        update_invited_user_form.find(".invited_user_id").val(invited_user_id);
        update_invited_user_form.find(".collaborator_id").val(collaborator_id);
        update_invited_user_form.find(".email").val(collaborator_email);
        update_invited_user_form.find(".update_value").val(COLLABORATOR_LEVEL[selected_action]);
        update_invited_user_form.trigger("submit");
    }
}

function onSubmitUpdateInvitedUser(event){
    event.preventDefault();
    let post_form = ux(event.target);

    ux().post(post_form.attr("action"), post_form.serialize(), async (response_data) => {
        if(response_data.status){
            let collaborator_id = `#invited_user_${response_data.result.invited_user_id}`;
            let invited_user_element = ux(collaborator_id);
            await invited_user_element.replaceWith(response_data.result.html);
            addAnimation(collaborator_id, "animated_blinkBorder");
            
            setTimeout(() => {
                ux(collaborator_id).findAll(".added_collaborator").forEach(dropdown_element => {
                    M.FormSelect.init(dropdown_element);
                    ux(dropdown_element).removeClass("added_collaborator");
                });
            });
        } else {

        }
    }, "json");

    return false;
}
function onSubmitRemoveInvitedUser(event){
    event.preventDefault();
    let post_form = ux(event.target);

    ux().post(post_form.attr("action"), post_form.serialize(), async (response_data) => {
        if(response_data.status){
            let invited_user_element = ux(`#invited_user_${response_data.result.invited_user_id}`);
            invited_user_element.addClass("animate__animated animate__fadeOut");
                
            invited_user_element.on("animationend", () => {
                invited_user_element.remove();
                
                let invite_collaborator_btn = document.getElementById("invite_collaborator_btn");
                let collaborator_count      = parseInt(response_data.result.cache_collaborators_count) + 1;

                if(invite_collaborator_btn){
                    invite_collaborator_btn.innerHTML = (`${collaborator_count} Collaborators`);
                }
            }, false);
        } else {

        }
    }, "json");

    return false;
}

function confirmRemoveInvitedUser(event){
    ux("#remove_invited_user_form").trigger("submit");
}
