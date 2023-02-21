// import data from "../json/large_dataset.json" assert { type: "json" };
document.addEventListener("DOMContentLoaded", async () => {
    let modal = document.querySelectorAll('.modal');
    let instances = M.Modal.init(modal);

    const invite_form = document.querySelector("#invite_form");
    invite_form.addEventListener("submit", submitInvite);

    /* Print all documentation */
    // displayDocumentations(data.documentations);

    initializeMaterializeDropdown();
    $("#add_documentation_form").on("submit", onSubmitAddDocumentationForm);
    appearEmptyDocumentation();

    $(".edit_title_icon").on("click", toggleEditDocumentationTitle);
    $(".duplicate_icon").on("click", duplicateDocumentation);
    $(".document_title").on("blur", onChangeDocumentationTitle);

    $(".active_docs_btn").on("click", appearActiveDocumentation);
    $(".archived_docs_btn").on("click", appearArchivedDocumentations);
    $(".remove_btn").on("click", setRemoveDocumentationValue);
    $("#remove_confirm").on("click", submitRemoveDocumentation);

    document.querySelectorAll("#documentations").forEach((section_tabs_list) => {
        Sortable.create(section_tabs_list);
    });

    // const email_address = document.querySelector("#email_address");    
    // email_address.addEventListener("keyup", validateEmail);

    document.addEventListener("click", (event) => {
        event.stopPropagation();
        event.preventDefault();

        let element = event.target.closest(".add_invite_result");
        
        if(element){
            addSearchEmailResult(element);
        }
    });

    $(".set_privacy_btn").on("click", setDocumentPrivacyValues);
    
    $(".change_privacy_yes_btn").on("click", submitChangeDocumentPrivacy);
    
    $(".document_block").on("click", redirectToDocumentView);

    $(".invite_collaborators_btn").on("click", function(event){
        event.stopImmediatePropagation();
        event.preventDefault();
        let invite_modal = document.querySelector("#modal1");
        var instance = M.Modal.getInstance(invite_modal);
        instance.open();
    });

    $(".access_btn").on("click", function(event){
        event.stopImmediatePropagation();
        event.preventDefault();
        let confirm_modal = document.querySelector("#confirm_to_public");
        var instance = M.Modal.getInstance(confirm_modal);
        instance.open();
    });
    
    $(".set_to_public_icon ").on("click", function(event){
        event.stopImmediatePropagation();
        event.preventDefault();
        let confirm_modal = document.querySelector("#confirm_to_public");
        var instance = M.Modal.getInstance(confirm_modal);
        instance.open();
    });

    $(".set_to_private_icon").on("click", function(event){
        event.stopImmediatePropagation();
        event.preventDefault();
        let confirm_modal = document.querySelector("#confirm_to_private");
        var instance = M.Modal.getInstance(confirm_modal);
        instance.open();
    });

    $(".archive_btn, .remove_btn").on("click", setRemoveArchiveValue);
    $("#archive_confirm").on("click", submitRemoveArchive);
    $("#remove_confirm").on("click", submitRemoveDocumentation);
    $("#remove_invited_user_confirm").on("click", submitRemoveInvitedUser);
    $("#add_invite_btn").on("click", addPeopleWithAccess);

    $(".invited_user_role").on("change", setRoleChangeAction);
    $(".sort_by").on("click", sort_documentations);

    /* run functions from invite_modal.js */
    initChipsInstance();
    // initRoleDropdown();
    initSelect();

    M.Dropdown.init($("#docs_view_btn")[0]);
    M.Dropdown.init($("#sort_by_btn")[0]);

    $("#get_documentations_form").on("submit", getDocumentations);
});

function getNewDocumentationId(event){
    let documentation_children = document.querySelectorAll("#documentations .document_block");
    let largest_id = 1;

    documentation_children.forEach(documentation_child => {
        let document_id = parseInt(documentation_child.id.split("_")[1]);

        if(document_id > largest_id){
            largest_id = document_id;
        }
    });

    // return largest_id + 1;
    return new Date().getTime();
}

function submitInvite(event){
    event.preventDefault();
}

function onSubmitAddDocumentationForm(event){
    event.preventDefault();
    let add_document_form = $(this);
    const input_document_title = $("#input_add_documentation").val();

    if(input_document_title){
        /** Use AJAX to generate new documentation */
        $.post(add_document_form.attr("action"), add_document_form.serialize(), (post_data) => {
            $("#documentations").prepend(post_data.html);
            
            setTimeout(() => {
                let document_block = $("#documentations .document_block")[0];
                $("#add_documentation_form")[0].reset();
                appearEmptyDocumentation();
    
                $(".set_privacy_btn").on("click", setDocumentPrivacyValues);
                $(".edit_title_icon").on("click", toggleEditDocumentationTitle);
                $(".duplicate_icon").on("click", duplicateDocumentation);
                $(".document_title").on("blur", onChangeDocumentationTitle);
                $(".archive_btn").on("click", setRemoveArchiveValue);
                $(".remove_btn").on("click", setRemoveArchiveValue);
                document_block.addEventListener("click", redirectToDocumentView);
                initializeMaterializeDropdown();
            }, 148);
        }, "json");
        
        return;
        window.location.href = "admin_edit_documentation.php"
    }
}

function displayDocumentations(documentations){
    const documentation_div = document.getElementById("documentations");
    let document_block = "";

    /* Print all documentation */
    documentations.forEach((document, index) => {
        document_block += `
            <div id="document_${index + 1}" class="document_block">
                <div class="document_details">
                    <input type="text" name="document_title" value="${document.title}" id="" class="document_title" readonly="">
                    ${ document.is_private ? `<button class="invite_collaborators_btn modal-trigger" href="#modal1"> ${document.collaborator_count}</button>` : ''}
                </div>
                <div class="document_controls">
                    ${ document.is_private ? `<button class="access_btn modal-trigger set_privacy_btn" href="#confirm_to_public" data-document_id="${index + 1}" data-document_privacy="private"></button>` : '' }
                    <button class="more_action_btn dropdown-trigger" data-target="document_more_actions_${ index + 1}">⁝</button>
                    <!-- Dropdown Structure -->
                    <ul id="document_more_actions_${ index + 1}" class="dropdown-content more_action_list_${ document.is_private ? "private" : "public" }">
                        <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                        <li class="divider" tabindex="-1"></li>
                        <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                        <li class="divider" tabindex="-1"></li>
                        <li><a href="#confirm_to_archive" class="archive_icon modal-trigger archive_btn" data-document_id="${index + 1}" data-documentation_action="archive">Archive</a></li>
                        <li class="divider" tabindex="-1"></li>
                        ${ document.is_private ? `<li><a href="#modal1" class="invite_icon modal-trigger">Invite</a></li>
                        <li class="divider" tabindex="-1"></li><li><a href="#confirm_to_public" class="set_to_public_icon modal-trigger set_privacy_btn" data-document_id="${index + 1}" data-document_privacy="private">Set to Public</a></li>` : 
                        `<li><a href="#confirm_to_private" class="set_to_private_icon modal-trigger set_privacy_btn" data-document_id="${index + 1}" data-document_privacy="public">Set to Private</a></li>` }
                        <li class="divider" tabindex="-1"></li>
                        <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="${index + 1}" data-documentation_action="remove">Remove</a></li>
                    </ul>
                </div>
            </div>`;
    });

    console.log(document_block);
}

function initializeMaterializeDropdown(){
    let elems = document.querySelectorAll('.more_action_btn');
    M.Dropdown.init(elems, {
        alignment: 'left',
        coverTrigger: false,
        constrainWidth: false
    });
}

function appearEmptyDocumentation(){
    let documentations_count = $("#documentations")[0].children.length;

    if(documentations_count < 2){
        $(".no_documents").removeClass("hidden");
    }else{
        $(".no_documents").addClass("hidden");
    }
    
    let archived_documents_count = $("#archived_documents")[0].children.length;
    if(archived_documents_count <= 1){
        $(".no_archived_documents").removeClass("hidden");
    }else{
        $(".no_archived_documents").addClass("hidden");
    }
}

function toggleEditDocumentationTitle(event){
    event.stopImmediatePropagation();
    let edit_title_btn = $(event.target);
    let document_block = edit_title_btn.closest(".document_block");
    let document_title = document_block.find(".document_details .document_title");
    let end = document_title.val().length;

    document_title[0].removeAttribute("readonly");
    document_title[0].setSelectionRange(0, end);
    
    setTimeout(() => {
        document_title[0].focus();
    });
}

function onChangeDocumentationTitle(event){
    let document_title = event.target;
    document_title.setAttribute("readonly", "");

    /** TODO: Submit change title form */
}

function duplicateDocumentation(event){
    event.stopImmediatePropagation();
    let source = event.target.closest(".document_block");
    let new_documentation_id = getNewDocumentationId();

    let cloned_documentation = $(source).clone();
    let cloned_title = cloned_documentation.find(".document_title");
    let cloned_target = cloned_documentation.find(".more_action_btn");
    let cloned_list = cloned_documentation.find(".dropdown-content");
    let more_action_title = `document_more_actions_${new_documentation_id}`;

    /* Update document_id of clone */
    cloned_documentation[0].setAttribute("id", `document_${new_documentation_id}`);
    cloned_documentation.find(".archive_btn")[0].dataset.document_id     = new_documentation_id;
    cloned_documentation.find(".remove_btn")[0].dataset.document_id      = new_documentation_id;

    cloned_title[0].setAttribute("style", "");
    cloned_title.val(`Copy of ${cloned_title.val()}`);
    cloned_target[0].setAttribute("data-target", more_action_title);
    cloned_list[0].setAttribute("id", more_action_title);
    cloned_list[0].setAttribute("style", "");

    $(cloned_documentation.on("click", redirectToDocumentView));
    $(cloned_documentation.find(".edit_title_icon").on("click", toggleEditDocumentationTitle));
    $(cloned_documentation.find(".duplicate_icon").on("click", duplicateDocumentation));
    $(cloned_documentation.find(".document_title").on("click", onChangeDocumentationTitle));
    $(cloned_documentation.find(".archive_btn").on("click", setRemoveArchiveValue));
    $(cloned_documentation.find(".remove_btn").on("click", setRemoveArchiveValue));
    cloned_documentation.find(".set_privacy_btn").on("click", setDocumentPrivacyValues);
    cloned_documentation.find(".set_privacy_btn").each( () => {
        $(this).attr("document_id", new_documentation_id);
    });
    
    cloned_documentation[0].className += " animate__animated animate__fadeIn";
    cloned_documentation.on("animationend", () => {
        cloned_documentation[0].classList.remove("animate__animated", "animate__fadeIn");
    }, false);

    source.insertAdjacentElement("afterend", cloned_documentation[0]);
    initializeMaterializeDropdown();
}

function appearActiveDocumentation(event){
    let active_docs_btn = event.target;
    let container = $(active_docs_btn).closest(".container");
    let docs_view_btn = $(container).find("#docs_view_btn")[0];

    docs_view_btn.innerText = active_docs_btn.innerText;
    $("#documentations").removeClass("hidden");
    $("#archived_documents").addClass("hidden");
    
    /* Update form value */
    $("#get_documentations_form #is_archived").val("0");
    $("#get_documentations_form").submit();
}

function appearArchivedDocumentations(event){
    let archived_docs_btn = event.target;
    let container = $(archived_docs_btn).closest(".container");
    let docs_view_btn = $(container).find("#docs_view_btn")[0];

    docs_view_btn.innerText = archived_docs_btn.innerText;
    $("#archived_documents").removeClass("hidden");
    $("#documentations").addClass("hidden");

    /* Update form value */
    $("#get_documentations_form #is_archived").val("1");
    $("#get_documentations_form").submit();
}

/* Will set values needed for changing a documentation's privacy. Values will be used after clicking 'Yes' on the modal */
function setDocumentPrivacyValues(event){
    const documentation         = event.target;
    const documentation_id      = documentation.getAttribute("data-document_id");
    const documentation_privacy = documentation.getAttribute("data-document_privacy");

    /* Set form values */
    document.getElementById("change_privacy_doc_id").value = documentation_id;
    document.getElementById("change_privacy_doc_privacy").value = documentation_privacy;
}

function submitChangeDocumentPrivacy(event){
    /* This is just for clickable prototype. Will replace all when form is submitted to the backend */
    const documentation_id      = document.getElementById("change_privacy_doc_id").value;
    const documentation_privacy = document.getElementById("change_privacy_doc_privacy").value;

    const document_details         = $(`#document_${documentation_id} .document_details`)[0];
    const dropdown_set_privacy_btn = $(`#document_${documentation_id} .dropdown-content .set_privacy_btn`)[0];

    const dropdown_content = $(`#document_more_actions_${documentation_id}`)[0];
    
    let href_value, document_privacy, class_name, inner_html = "";

    /* When changing privacy to Public... */
    if(documentation_privacy == "private"){
        document_details.querySelector(".invite_collaborators_btn").remove();
        $(`#document_${documentation_id} .access_btn`)[0].remove();

        /* Set set_privacy_btn values */
        href_value       = "#confirm_to_private";
        document_privacy = "public";
        class_name       = "set_to_private_icon modal-trigger set_privacy_btn";
        inner_html       = "Set to Private"

        dropdown_content.innerHTML = `
            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="#confirm_to_archive" class="archive_icon modal-trigger archive_btn" data-document_id="${documentation_id}" data-documentation_action="archive">Archive</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="#confirm_to_private" class="set_to_public_icon modal-trigger set_privacy_btn" data-document_id="${documentation_id}" data-document_privacy="public">Set to Private</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="${documentation_id}" data-documentation_action="remove">Remove</a></li>
        `;
    }
    /* When changing privacy to Private... */
    else {
        /* Create invite_collaborators_btn element */
        const invite_collaborators_btn = document.createElement("button");
        invite_collaborators_btn.innerHTML = 0;
        invite_collaborators_btn.className = "invite_collaborators_btn modal-trigger";
        invite_collaborators_btn.setAttribute("href", "#modal1");

        document_details.append(invite_collaborators_btn);

        /* Create access_btn */
        const access_btn = document.createElement("button");
        access_btn.className = "access_btn modal-trigger set_privacy_btn";
        access_btn.setAttribute("href", "#confirm_to_public");
        access_btn.setAttribute("data-document_id", documentation_id);
        access_btn.setAttribute("data-document_privacy", "private");
        access_btn.addEventListener("click", setDocumentPrivacyValues);

        $(`#document_${documentation_id} .document_controls`)[0].prepend(access_btn);

        /* Set set_privacy_btn values */
        href_value       = "#confirm_to_public";
        document_privacy = "private";
        class_name       = "set_to_public_icon modal-trigger set_privacy_btn";
        inner_html       = "Set to Public"

        dropdown_content.innerHTML = `
        <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
        <li class="divider" tabindex="-1"></li>
        <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
        <li class="divider" tabindex="-1"></li>
        <li><a href="#confirm_to_archive" class="archive_icon modal-trigger archive_btn" data-document_id="${documentation_id}" data-documentation_action="archive">Archive</a></li>
        <li class="divider" tabindex="-1"></li>
        <li><a href="#modal1" class="invite_icon modal-trigger">Invite</a></li>
        <li class="divider" tabindex="-1"></li>
        <li><a href="#confirm_to_public" class="set_to_public_icon modal-trigger set_privacy_btn" data-document_id="${documentation_id}" data-document_privacy="private">Set to Public</a></li>
        <li class="divider" tabindex="-1"></li>
        <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="${documentation_id}" data-documentation_action="remove">Remove</a></li>
        `;
    }

    /* Update dropdown */
    dropdown_content.className = `dropdown-content more_action_list_${document_privacy}`;
    dropdown_set_privacy_btn.setAttribute("href", href_value);
    dropdown_set_privacy_btn.setAttribute("data-document_privacy", document_privacy);
    dropdown_set_privacy_btn.className = class_name;
    dropdown_set_privacy_btn.innerHTML = inner_html;

    $(".set_privacy_btn").on("click", setDocumentPrivacyValues);
    $(".edit_title_icon").on("click", toggleEditDocumentationTitle);
    $(".duplicate_icon").on("click", duplicateDocumentation);
    $(".document_title").on("blur", onChangeDocumentationTitle);
    $(".archive_btn").on("click", setRemoveArchiveValue);
    $(".remove_btn").on("click", setRemoveArchiveValue);
}

function setRemoveDocumentationValue(event){
    event.stopImmediatePropagation();

    const documentation    = $(this);
    const documentation_id = documentation.data("document_id");

    /* Set form values */
    $("#remove_documentation_form #remove_documentation_id").val(documentation_id);

    let remove_modal = document.querySelector("#confirm_to_remove");
    var instance = M.Modal.getInstance(remove_modal);
    instance.open();
}

function submitRemoveDocumentation(event){
    event.stopImmediatePropagation();
    event.preventDefault();

    let form = $("#remove_documentation_form");

    $.post(form.attr("action"), form.serialize(), (response_data) => {
        let documentation = document.getElementById(`document_${response_data.result.documentation_id}`);

        documentation.className += " animate__animated animate__fadeOut";
        documentation.addEventListener("animationend", () => {
            documentation.remove();
        }, false);
    }, "json");

    let remove_modal = document.querySelector("#confirm_to_remove");
    var instance = M.Modal.getInstance(remove_modal);
    instance.close();

    return false;
}

function redirectToDocumentView(event){
    if(event.target.classList.contains("set_privacy_btn") || 
        event.target.classList.contains("more_action_btn") || 
        event.target.classList.contains("invite_collaborators_btn") || 
        event.target.closest("li")){
            return;
    }

    location.href = "admin_edit_documentation.php";
}

function sort_documentations(event){
    let sort_by = $(event.target).attr("data-sort-by");
    let documentation_lists = document.getElementById('documentations');
    let documentation_list_nodes = documentation_lists.childNodes;

    let documentation_lists_to_sort = [];

    for (let i in documentation_list_nodes) {
        (documentation_list_nodes[i].nodeType == 1) && documentation_lists_to_sort.push(documentation_list_nodes[i]);
    }
    
    documentation_lists_to_sort.sort(function(a, b) {
        return a.innerHTML == b.innerHTML ? 0 : ( sort_by === "az" ? (a.innerHTML > b.innerHTML ? 1 : -1) : (b.innerHTML > a.innerHTML ? 1 : -1) );
    });

    for (let i = 0; i < documentation_lists_to_sort.length; i++) {
        documentation_lists.appendChild(documentation_lists_to_sort[i]);
    }
}

function getDocumentations(event){
    event.preventDefault();
    let form = $(this);
    
    $.post(form.attr("action"), form.serialize(), (response_data) => {
        let documentations_div = $("#get_documentations_form #is_archived").val() == "1" ? "#archived_documents" : "#documentations";

        $(documentations_div).html(response_data.result.html);
        initializeMaterializeDropdown();
    }, "json");

    return false;
}

function setRemoveArchiveValue(){
    
}

function submitRemoveArchive(){

}