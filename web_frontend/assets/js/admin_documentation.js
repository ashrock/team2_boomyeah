// import data from "../json/large_dataset.json" assert { type: "json" };
document.addEventListener("DOMContentLoaded", async () => {
    const current_location = window.location.pathname;
    const view_path = current_location.substring(0, current_location.lastIndexOf('/'));

    let global_path = (view_path === "/views")? "." : "..";
    let assets_path = (view_path === "/views" )? ".." : "../..";

    let modal = document.querySelectorAll('.modal');
    let instances = M.Modal.init(modal);

    const invite_form = document.querySelector("#invite_form");
    invite_form.addEventListener("submit", submitInvite);

    /* Print all documentation */
    // displayDocumentations(data.documentations);

    initializeMaterializeDropdown();
    ux("#add_documentation_form").on("submit", submitDocForm);
    appearEmptyDocumentation();

    ux(".edit_title_icon").onEach("click", editTitleDocumentation);
    ux(".duplicate_icon").onEach("click", duplicateDocumentation);
    ux(".document_title").onEach("blur", disableEditTitleDocumentation);

    ux(".active_docs_btn").onEach("click", appearActiveDocumentation);
    ux(".archived_docs_btn").onEach("click", appearArchivedDocumentations);

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

    ux(".set_privacy_btn").onEach("click", setDocumentPrivacyValues);
    
    ux(".change_privacy_yes_btn").onEach("click", submitChangeDocumentPrivacy);
    
    ux(".document_block").onEach("click", redirectToDocumentView);

    ux(".invite_collaborators_btn").onEach("click", function(event){
        event.stopImmediatePropagation();
        let invite_modal = document.querySelector("#modal1");
        var instance = M.Modal.getInstance(invite_modal);
        instance.open();
    });

    ux(".access_btn").onEach("click", function(event){
        event.stopImmediatePropagation();
        let confirm_modal = document.querySelector("#confirm_to_public");
        var instance = M.Modal.getInstance(confirm_modal);
        instance.open();
    });
    
    ux(".set_to_public_icon ").onEach("click", function(event){
        event.stopImmediatePropagation();
        let confirm_modal = document.querySelector("#confirm_to_public");
        var instance = M.Modal.getInstance(confirm_modal);
        instance.open();
    });

    ux(".set_to_private_icon").onEach("click", function(event){
        event.stopImmediatePropagation();
        let confirm_modal = document.querySelector("#confirm_to_private");
        var instance = M.Modal.getInstance(confirm_modal);
        instance.open();
    });

    ux(".archive_btn, .remove_btn").onEach("click", setRemoveArchiveValue);
    ux("#archive_confirm, #remove_confirm").onEach("click", submitRemoveArchive);
    ux("#remove_invited_user_confirm").onEach("click", submitRemoveInvitedUser);
    ux("#add_invite_btn").on("click", addPeopleWithAccess);

    ux(".invited_user_role").onEach("change", setRoleChangeAction);
    ux(".sort_by").onEach("click", sort_documentations);

    /* run functions from invite_modal.js */
    initChipsInstance();
    // initRoleDropdown();
    initSelect();

    M.Dropdown.init(ux("#docs_view_btn").html());
    M.Dropdown.init(ux("#sort_by_btn").html());
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

    return largest_id + 1;
}

function submitInvite(event){
    event.preventDefault();
}

function submitDocForm(event){
    event.preventDefault();
    const input_document_title = ux("#input_add_documentation").html().value;

    if(input_document_title){
        const new_documentation_id   = getNewDocumentationId();
        const document_block = document.createElement("div");

        /* Create document_block */
        document_block.setAttribute("id", `document_${new_documentation_id}`);
        document_block.className = "document_block";
        
        /* Create document_details */
        const document_details = document.createElement("div");
        document_details.className = "document_details";

        /* Create document_details child element */
        const document_title = document.createElement("input");
        document_title.className = "document_title";
        document_title.readOnly = true;
        document_title.setAttribute("name", "document_title");
        document_title.setAttribute("value", input_document_title);
        document_details.appendChild(document_title);

        /* Create document_controls */
        const document_controls = document.createElement("div");
        document_controls.className = "document_controls"

        /* Create document_controls child more_action_btn */
        const more_action_btn = document.createElement("button");
        more_action_btn.innerHTML = "⁝";
        more_action_btn.className = "more_action_btn dropdown-trigger";
        more_action_btn.dataset.target = `document_more_actions_${new_documentation_id}`;
        document_controls.appendChild(more_action_btn);

        /* Create document_controls child dropdown-content */
        const dropdown_content = document.createElement("ul");
        dropdown_content.setAttribute("id", `document_more_actions_${new_documentation_id}`);
        dropdown_content.setAttribute("tabindex", "0");
        dropdown_content.className = "dropdown-content more_action_list_public";
        
        /* Create lists */
        const list_actions = [
            {
                "action_name": "Edit Title",
                "action_class": "edit_title_btn",
                "anchor_href": "#!",
                "icon_class": "edit_title_icon",
                "dataset": null
            },
            {
                "action_name": "Duplicate",
                "action_class": null,
                "anchor_href": "#!",
                "icon_class": "duplicate_icon",
                "dataset": null
            },
            {
                "action_name": "Archive",
                "action_class": null,
                "anchor_href": "#confirm_to_archive",
                "icon_class": "archive_icon modal-trigger archive_btn",
                "dataset": { "documentation_action": "archive" }
            },
            {
                "action_name": "Set to Private",
                "action_class": null,
                "anchor_href": "#confirm_to_private",
                "icon_class": "set_to_private_icon modal-trigger set_privacy_btn",
                "dataset": { "document_privacy": "public" }
            },
            {
                "action_name": "Remove",
                "action_class": null,
                "anchor_href": "#confirm_to_remove",
                "icon_class": "remove_icon modal-trigger remove_btn",
                "dataset": { "documentation_action": "remove" }
            }
        ]

        list_actions.forEach((action, key) => {
            let list_action = document.createElement("li");
            let list_icon   = document.createElement("a");

            list_action.setAttribute("tabindex", "0");
            if(action.action_class)
                list_action.className = action.action_class;

            list_icon.className   = action.icon_class;
            list_icon.setAttribute("href", action.anchor_href);
            list_icon.innerHTML += action.action_name;

            if(action.dataset){
                list_icon.dataset.document_id = new_documentation_id;
                
                for(let [key, value] of Object.entries(action.dataset)){
                    list_icon.dataset[key] = value;
                }
            }

            list_action.appendChild(list_icon);
            dropdown_content.appendChild(list_action);

            if(key < 4){
                let li_divider  = document.createElement("li");
                li_divider.setAttribute("tabindex", "-1");
                li_divider.className = "divider";
                dropdown_content.appendChild(li_divider);
            }
        });

        document_controls.appendChild(dropdown_content);

        document_block.appendChild(document_details);
        document_block.appendChild(document_controls);
        document_block.className += " animate__animated animate__fadeIn";
        document_block.addEventListener("animationend", () => {
            document_block.classList.remove("animate__animated", "animate__fadeIn");
        }, false);

        ux("#documentations").html().appendChild(document_block);
        ux("#add_documentation_form").html().reset();
        appearEmptyDocumentation();

        ux(".set_privacy_btn").onEach("click", setDocumentPrivacyValues);
        ux(".edit_title_icon").onEach("click", editTitleDocumentation);
        ux(".duplicate_icon").onEach("click", duplicateDocumentation);
        ux(".document_title").onEach("blur", disableEditTitleDocumentation);
        ux(".archive_btn").onEach("click", setRemoveArchiveValue);
        ux(".remove_btn").onEach("click", setRemoveArchiveValue);
        document_block.addEventListener("click", redirectToDocumentView);
        initializeMaterializeDropdown();

        window.location.href = "../no_data/admin_edit_documentation.html"
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
    let documentations_count = ux("#documentations").html().children.length;

    if(documentations_count < 2){
        ux(".no_documents").removeClass("hidden");
    }else{
        ux(".no_documents").addClass("hidden");
    }
    
    let archived_documents_count = ux("#archived_documents").html().children.length;
    if(archived_documents_count <= 1){
        ux(".no_archived_documents").removeClass("hidden");
    }else{
        ux(".no_archived_documents").addClass("hidden");
    }
}

function editTitleDocumentation(event){
    event.stopImmediatePropagation();
    let edit_title_btn = event.target;
    let document_block = ux(edit_title_btn.closest(".document_block"));
    let document_title = ux(document_block.find(".document_details .document_title")).html();
    let end = document_title.html().value.length;

    document_title.html().removeAttribute("readonly");
    document_title.html().setSelectionRange(end, end);
    setTimeout(() => document_title.html().focus(), 0);
}

function disableEditTitleDocumentation(event){
    let document_title = event.target;
    // Will confirm
    // let parent_div = document_title.parentNode.parentNode;

    // parent_div.className += " animate__animated animate__pulse";
    
    document_title.setAttribute("readonly", "");
}

function duplicateDocumentation(event){
    event.stopImmediatePropagation();
    let source = event.target.closest(".document_block");
    let new_documentation_id = getNewDocumentationId();

    let cloned = ux(source).clone();
    let cloned_title = ux(cloned.find(".document_title")).html();
    let cloned_target = ux(cloned.find(".more_action_btn")).html();
    let cloned_list = ux(cloned.find(".dropdown-content")).html();
    let more_action_title = `document_more_actions_${new_documentation_id}`;

    /* Update document_id of clone */
    cloned.html().setAttribute("id", `document_${new_documentation_id}`);
    cloned.find(".archive_btn").html().dataset.document_id     = new_documentation_id;
    cloned.find(".remove_btn").html().dataset.document_id      = new_documentation_id;

    cloned_title.html().setAttribute("style", "");
    cloned_title.html().value = `Copy of ${cloned_title.html().value}`;
    cloned_target.html().setAttribute("data-target", more_action_title);
    cloned_list.html().setAttribute("id", more_action_title);
    cloned_list.html().setAttribute("style", "");

    ux(cloned.on("click", redirectToDocumentView));
    ux(cloned.find(".edit_title_icon").on("click", editTitleDocumentation));
    ux(cloned.find(".duplicate_icon").on("click", duplicateDocumentation));
    ux(cloned.find(".document_title").on("click", disableEditTitleDocumentation));
    ux(cloned.find(".archive_btn").on("click", setRemoveArchiveValue));
    ux(cloned.find(".remove_btn").on("click", setRemoveArchiveValue));
    cloned.findAll((".set_privacy_btn")).forEach( element => {
        element.dataset.document_id = new_documentation_id;
        element.addEventListener("click", setDocumentPrivacyValues);
    });
    
    cloned.html().className += " animate__animated animate__fadeIn";
    cloned.html().addEventListener("animationend", () => {
        cloned.html().classList.remove("animate__animated", "animate__fadeIn");
    }, false);

    source.insertAdjacentElement("afterend", cloned.html());
    initializeMaterializeDropdown();
}

function appearActiveDocumentation(event){
    let active_docs_btn = event.target;
    let container = ux(active_docs_btn.closest(".container"));
    let docs_view_btn = ux(container.find("#docs_view_btn")).html();

    docs_view_btn.html().innerText = active_docs_btn.innerText;
    ux("#documentations").removeClass("hidden");
    ux("#archived_documents").addClass("hidden");
    window.location.reload();
}

function appearArchivedDocumentations(event){
    let archived_docs_btn = event.target;
    let container = ux(archived_docs_btn.closest(".container"));
    let docs_view_btn = ux(container.find("#docs_view_btn")).html();

    docs_view_btn.html().innerText = archived_docs_btn.innerText;
    ux("#archived_documents").removeClass("hidden");
    ux("#documentations").addClass("hidden");
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

    const document_details         = ux(`#document_${documentation_id} .document_details`).html();
    const dropdown_set_privacy_btn = ux(`#document_${documentation_id} .dropdown-content .set_privacy_btn`).html();

    const dropdown_content = ux(`#document_more_actions_${documentation_id}`).html();
    
    let href_value, document_privacy, class_name, inner_html = "";

    /* When changing privacy to Public... */
    if(documentation_privacy == "private"){
        document_details.querySelector(".invite_collaborators_btn").remove();
        ux(`#document_${documentation_id} .access_btn`).html().remove();

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

        ux(`#document_${documentation_id} .document_controls`).html().prepend(access_btn);

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

    ux(".set_privacy_btn").onEach("click", setDocumentPrivacyValues);
    ux(".edit_title_icon").onEach("click", editTitleDocumentation);
    ux(".duplicate_icon").onEach("click", duplicateDocumentation);
    ux(".document_title").onEach("blur", disableEditTitleDocumentation);
    ux(".archive_btn").onEach("click", setRemoveArchiveValue);
    ux(".remove_btn").onEach("click", setRemoveArchiveValue);
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

    ux(`#document_${documentation_id}`).html().className += " animate__animated animate__fadeOut";
    ux(`#document_${documentation_id}`).html().addEventListener("animationend", () => {
        ux(`#document_${documentation_id}`).html().remove();
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

    location.href = "admin_edit_documentation.html";
}

function sort_documentations(event){
    let sort_by = ux(event.target).attr("data-sort-by");
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