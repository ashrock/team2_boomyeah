var valid_email = true;
var invited_emails = [];

var invite_instance = null;
var role_instance   = null;

function initSelect(){
    var select_instance = M.FormSelect.init(document.querySelectorAll('select'));
}

function initChipsInstance(){
    var invite_instance = M.Chips.init(document.querySelector(".chips"), {
        placeholder: "Enter email address",
        secondaryPlaceholder: "Enter email address",
        onChipAdd: (e, email) => {
            addEmail(email.innerText.split("close")[0]);
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
        invited_emails.forEach( email => {
            const invited_user  = document.createElement("div");
            invited_user.id        = `invited_user_${new_invited_user_id}`;
            invited_user.className = "invited_user";

            const user_information = document.createElement("div");
            user_information.className = "invited_user_info";

            const email_text = document.createElement("p");
            email_text.innerHTML = email;
            user_information.appendChild(email_text);
            invited_user.appendChild(user_information);

            const invited_user_role = document.createElement("select");
            invited_user_role.setAttribute("id", "invited_user_role");
            invited_user_role.className = "invited_user_role";
            invited_user_role.dataset.invited_user_id = new_invited_user_id;

            const viewer_option         = document.createElement("option");
            viewer_option.value         = "Viewer";
            viewer_option.innerHTML     = "Viewer";
            invited_user_role.appendChild(viewer_option);

            const editor_option      = document.createElement("option");
            editor_option.value         = "Editor";
            editor_option.innerHTML     = "Editor";
            invited_user_role.appendChild(editor_option);
            
            const remove_option      = document.createElement("option");
            remove_option.value      = "remove";
            remove_option.innerHTML  = "Remove";
            invited_user_role.appendChild(remove_option);

            invited_user.appendChild(invited_user_role);

            $(".invited_users_wrapper")[0].appendChild(invited_user);
            $("#remove_invited_user_confirm").on("click", submitRemoveInvitedUser);
            $(".invited_user_role").on("change", setRoleChangeAction);

            initSelect();

            new_invited_user_id++;
        });
    }

    invited_emails = [];
    initChipsInstance();
}

function setRoleChangeAction(event){
    const selected_action = event.target.value;
    const invited_user_id = event.target.dataset.invited_user_id;

    if(selected_action === "remove"){
        let remove_invited_user_modal = document.querySelector("#confirm_to_remove_invited_user");
        var instance = M.Modal.getInstance(remove_invited_user_modal);
        instance.open();

        $("#invited_user_id")[0].value = invited_user_id;
    }
    else{
        // for changing role to viewer/editor in the backend
    }
}

function submitRemoveInvitedUser(event){
    const invited_user_id      = $("#invited_user_id")[0].value;
    const invited_user_element = $(`#invited_user_${invited_user_id}`)[0];

    invited_user_element.className += " animate__animated animate__fadeOut";
        
    invited_user_element.addEventListener("animationend", () => {
        invited_user_element.remove();
    }, false);
}
