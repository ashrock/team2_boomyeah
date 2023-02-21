// import data from "../json/large_dataset.json" assert { type: "json" };
document.addEventListener("DOMContentLoaded", async () => {
    let modal = document.querySelectorAll('.modal');
    let instances = M.Modal.init(modal);

    const invite_form = document.querySelector("#invite_form");
    invite_form.addEventListener("submit", submitInvite);

    /* Print all documentation */
    // displayDocumentations(data.documentations);

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

    $(".set_privacy_btn").on("click", setDocumentPrivacyValues);
    
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


    $("#add_documentation_form").on("submit", onSubmitAddDocumentationForm);
    $("#duplicate_documentation_form").on("submit", onSubmitDuplicateForm);
    $("#change_document_privacy_form").on("submit", onSubmitChangePrivacy);
    appearEmptyDocumentation();

    $(".edit_title_icon").on("click", toggleEditDocumentationTitle);
    $(".duplicate_icon").on("click", duplicateDocumentation);
    $(".document_title").on("blur", onChangeDocumentationTitle);

    $(".active_docs_btn").on("click", appearActiveDocumentation);
    $(".archived_docs_btn").on("click", appearArchivedDocumentations);
    $(".archive_btn, .remove_btn").on("click", setRemoveArchiveValue);
    $("#archive_confirm, #remove_confirm").on("click", submitRemoveArchive);
    $("#remove_invited_user_confirm").on("click", submitRemoveInvitedUser);
    $("#add_invite_btn").on("click", addPeopleWithAccess);

    $(".invited_user_role").on("change", setRoleChangeAction);
    $(".sort_by").on("click", sort_documentations);

    /* run functions from invite_modal.js */
    initChipsInstance();
    initSelect();
    initializeMaterializeDropdown();

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
            if(post_data.status){
                $("#documentations").prepend(post_data.result.html);
                
                setTimeout(() => {
                    initializeMaterializeDropdown();
                }, 148);
            }

            $("#add_documentation_form")[0].reset();
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

function onSubmitDuplicateForm(event){
    event.preventDefault();
    event.stopImmediatePropagation();
    let post_form = $(this);
    let document_id = post_form.find(".documentation_id").val();

    /** Use AJAX to generate new documentation */
    $.post(post_form.attr("action"), post_form.serialize(), (post_data) => {
        if(post_data.status){
            // Append duplicated documentation
            $(`#document_${document_id}`).after(post_data.result.html);
            
            setTimeout(() => {
                initializeMaterializeDropdown();
            }, 148);
        }

        post_form[0].reset();
    }, "json");

    return false;  
}

function duplicateDocumentation(event){
    event.stopImmediatePropagation();
    event.preventDefault();
    let document_id = $(this).attr("data-document_id");
    let duplicate_form = $("#duplicate_documentation_form");
    duplicate_form.find(".documentation_id").val(document_id);
    duplicate_form.trigger("submit");

    return;
}

function appearActiveDocumentation(event){
    let active_docs_btn = event.target;
    let container = $(active_docs_btn).closest(".container");
    let docs_view_btn = $(container).find("#docs_view_btn")[0];

    docs_view_btn.innerText = active_docs_btn.innerText;
    $("#documentations").removeClass("hidden");
    $("#archived_documents").addClass("hidden");
    window.location.reload();
}

function appearArchivedDocumentations(event){
    let archived_docs_btn = event.target;
    let container = $(archived_docs_btn).closest(".container");
    let docs_view_btn = $(container).find("#docs_view_btn")[0];

    docs_view_btn.innerText = archived_docs_btn.innerText;
    $("#archived_documents").removeClass("hidden");
    $("#documentations").addClass("hidden");

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

function onSubmitChangePrivacy(event){
    event.stopImmediatePropagation();
    event.preventDefault();
    let post_form = $(this);
    let document_id = post_form.find(".documentation_id").val();

    /** Use AJAX to change documentation privacy */
    $.post(post_form.attr("action"), post_form.serialize(), (post_data) => {
        if(post_data.status){
            // Replace with updated documentation
            $(`#document_${document_id}`).replaceWith(post_data.result.html);
            
            setTimeout(() => {
                initializeMaterializeDropdown();
            }, 148);
        }

        post_form[0].reset();
    }, "json");
    return false;
}

function submitChangeDocumentPrivacy(event){
    event.preventDefault();
    $("#change_document_privacy_form").trigger("submit");
    return;
}

function setRemoveArchiveValue(event){
    const documentation        = event.target;
    const documentation_id     = documentation.getAttribute("data-document_id");
    const documentation_action = documentation.getAttribute("data-documentation_action");

    /* Set form values */
    document.getElementById("remove_archive_id").value    = documentation_id;
    document.getElementById("documentation_action").value = documentation_action;
}

function submitRemoveArchive(event){
    /* This is just for clickable prototype. Will replace all when form is submitted to the backend */
    const documentation_id = document.getElementById("remove_archive_id").value;
    
    /* Will not need this for now but will be used when form is submitted to the backend */
    const documentation_action = document.getElementById("documentation_action").value;

    $(`#document_${documentation_id}`)[0].className += " animate__animated animate__fadeOut";
    $(`#document_${documentation_id}`)[0].addEventListener("animationend", () => {
        $(`#document_${documentation_id}`)[0].remove();
    });

    appearEmptyDocumentation();
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
        $("#archived_documents").html(response_data.result.html);
        initializeMaterializeDropdown();
    }, "json");

    return false;
}