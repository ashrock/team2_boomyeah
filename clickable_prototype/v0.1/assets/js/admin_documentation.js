// import data from "../json/large_dataset.json" assert { type: "json" };
document.addEventListener("DOMContentLoaded", async () => {
    let modal = document.querySelectorAll('.modal');
    let instances = M.Modal.init(modal);

    const invite_form = document.querySelector("#invite_form");
    invite_form.addEventListener("submit", submitInvite);

    /* Print all documentation */
    // displayDocumentations(data.documentations);

    document.querySelectorAll("#documentations").forEach((section_tabs_list) => {
        Sortable.create(section_tabs_list, {
            onEnd: () => {
                updateDocumentationsOrder(section_tabs_list);
            }
        });
    });

    // $("#documentations").sortable();

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

        let change_document_privacy_form = $("#change_document_privacy_form");
        change_document_privacy_form.find("#documentation_id").val($(this).attr("data-document_id"));
        change_document_privacy_form.find("#update_value").val(0);    
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
    $(".archive_btn").on("click", setRemoveArchiveValue);
    
    $("#archive_confirm").on("click", submitRemoveArchive);
    $(".remove_btn").on("click", setRemoveDocumentationValue);
    $("#remove_confirm").on("click", submitRemoveDocumentation);
    $("#remove_invited_user_confirm").on("click", submitRemoveInvitedUser);
    $("#add_invite_btn").on("click", addPeopleWithAccess);

    $(".invited_user_role").on("change", setRoleChangeAction);
    $(".sort_by").on("click", sort_documentations);
    $("#reorder_documentations_form").on("submit", submitReorderDocumentations);

    /* run functions from invite_modal.js */
    initChipsInstance();
    initSelect();
    initializeMaterializeDropdown();

    M.Dropdown.init($("#docs_view_btn")[0]);
    M.Dropdown.init($("#sort_by_btn")[0]);

    $("#get_documentations_form").on("submit", getDocumentations);
});

$(document).ready(function(){
    $("body")
        .on("click", ".archive_btn, .remove_btn", setRemoveArchiveValue)
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
        $.post(add_document_form.attr("action"), add_document_form.serialize(), (data) => {
            if(data.status){
                /* TODO: Update once the admin edit documentation is added in v2. Change to redirect in admin edit document page. */
                alert("Documentation added succesfully!");
                $("#add_documentation_form")[0].reset();
            }
            else{
                alert(data.error);
            }
        }, "json");
        
        return;
    }
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
    event.preventDefault();

    let document_title = event.target;
    document_title.setAttribute("readonly", "");

    let document_title_input = $(this);
    let edit_doc_title_form = document_title_input.closest(".edit_title_form");

    if(document_title_input.val()){
        /** Use AJAX to generate new documentation */
        $.post(edit_doc_title_form.attr("action"), edit_doc_title_form.serialize(), (data) => {
            if(data.status){
                /* TODO: Improve UX after success updating of title. Add animation. */

            }
            else{
                /* TODO: Improve UX after updating empty title. Add animation red border. */
                alert(data.error);
            }
        }, "json");
    }
    else{
        alert("Please add documentation title");
        edit_doc_title_form.parent().addClass("error");
    }

    return;
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

            let documentation = $(`#document_${post_data.result.documentation_id}`);
            documentation.addClass("animate__animated animate__fadeIn");
            documentation.on("animationend", () => {
                documentation.removeClass("animate__animated", "animate__fadeIn");
            });

            $(".remove_btn").on("click", setRemoveDocumentationValue);
            initializeMaterializeDropdown();
        }

        post_form[0].reset();
    }, "json");

    return false;  
}

function duplicateDocumentation(event){
    event.stopImmediatePropagation();
    event.preventDefault();

    let document_id = $(this).data("document_id");
    let duplicate_form = $("#duplicate_documentation_form");
    duplicate_form.find(".documentation_id").val(document_id);
    duplicate_form.trigger("submit");

    return false;
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
    let change_document_privacy_form = $("#change_document_privacy_form");
    
    change_document_privacy_form.find("#documentation_id").val(documentation_id);
    change_document_privacy_form.find("#update_value").val( (documentation_privacy == "public") ? 1 : 0 );
}

function onSubmitChangePrivacy(event){
    event.stopImmediatePropagation();
    event.preventDefault();
    let post_form = $(this);
    
    /** Use AJAX to change documentation privacy */
    $.post(post_form.attr("action"), post_form.serialize(), (post_data) => {
        console.log('post_data', post_data);
        if(post_data.status){
            /* TODO: Improve UX after success updating. Add animation to indication the replace with the updated . */
            console.log('post_data.result.document_id', post_data.result.documentation_id)
            console.log('post_data.result.html', post_data.result.html);

            $(`#document_${documentation_id}`).replaceWith(post_data.result.html);

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
    let archive_button = $(this);
    let document_id     = archive_button.attr("data-document_id");
    let document_action = archive_button.attr("data-documentation_action");
    $("#confirm_to_archive").find("p").text("Are you sure you want to Unarchive this documentation?");
    
    /* Set form values */
    let archive_document_form = $("#remove_archive_form");
    archive_document_form.find("#documentation_id").val(document_id);
    archive_document_form.find("#update_value").val( (document_action == "archive") ? 1 : 0 );
}

function submitRemoveArchive(event){
    let archive_document_form = $("#remove_archive_form");

    $.post(archive_document_form.attr("action"), archive_document_form.serialize(), (data) => {
        if(data.status){
            /* TODO: Improve UX after success updating. Add animation to remove the archived document from the list. */
            
            appearEmptyDocumentation();
        }
        else{
            /* TODO: Improve UX after error. Add animation red border. */
            alert(data.error);
        }
    }, "json");
    
    return;
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
        let documentation = $(`#document_${response_data.result.documentation_id}`);

        documentation.addClass("animate__animated animate__fadeOut");
        documentation.on("animationend", () => {
            documentation.remove();
        });

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

        $(".remove_btn").on("click", setRemoveDocumentationValue);
        initializeMaterializeDropdown();
    }, "json");

    return false;
}

function updateDocumentationsOrder(documentations){
    let documentation_children = documentations.children;
    var new_documentations_order = "";

    /* Get documentation_id from documentation_children */
    for(let index=0; index < documentation_children.length; index++){
        new_documentations_order += (index == (documentation_children.length - 1)) ? `${documentation_children[index].id.split("_")[1]}` : `${documentation_children[index].id.split("_")[1]},`;
    }

    /* Update form value and submit form */
    $("#reorder_documentations_form #documentations_order").val(new_documentations_order);
    $("#reorder_documentations_form").submit();
}

function submitReorderDocumentations(event){
    event.preventDefault();
    let form = $(this);

    $.post(form.attr("action"), form.serialize(), (response_data) => {
        if(!response_data.status){
            alert("An error occured while reordering documentations!");
        }
    }, "json");

    return false;
}