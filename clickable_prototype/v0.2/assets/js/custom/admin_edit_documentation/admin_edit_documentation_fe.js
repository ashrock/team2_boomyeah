document.addEventListener("DOMContentLoaded", async () => {
    const add_section_form = document.querySelector("#section_form");
    add_section_form.addEventListener("submit", submitAddSectionForm);
    ux("body")
        .on("click", ".toggle_switch", switchText)
        .on("click", ".edit_title_icon", editSectionTitle)
        .on("blur", ".section_title", disableEditSectionTitle)
        .on("click", ".duplicate_icon", duplicateSection)
        .on("click", ".remove_icon", setRemoveSectionBlock)
        .on("click", "#remove_confirm", removeSectionBlock);

    Sortable.create(document.querySelector(".section_container"));
    
    initializeMaterializeTooltip();
    appearEmptySection();

    document.addEventListener("click", (event) => {
        let element = event.target.closest(".add_invite_result");
        
        if(element){
            addSearchEmailResult(element);
        }
    });
    
    initializeMaterializeDropdown();

    ux(".section_block").onEach("click", redirectToEditSection);

    ux(".more_action_btn").onEach("click", function(event){
        event.stopImmediatePropagation();
    });

    ux(".copy_icon").onEach("click", function(event){
        event.stopImmediatePropagation();
    });
    
    ux(".remove_icon").onEach("click", function(event){
        event.stopImmediatePropagation();
    });

    let modal = document.querySelectorAll('.modal');
    let instances = M.Modal.init(modal);
    
    ux("#add_invite_btn").on("click", addPeopleWithAccess);
    ux("#remove_invited_user_confirm").onEach("click", submitRemoveInvitedUser);
    ux(".invited_user_role").onEach("change", setRoleChangeAction);

    ux(".sort_by").onEach("click", sort_sections);

    /* run functions from invite_modal.js */
    initChipsInstance();
    // initRoleDropdown();
    initSelect();
});

function getNewSectionId(event){
    let sections = document.querySelectorAll("#section_container .section_block");
    let largest_id = 1;

    sections.forEach(section => {
        let section_id = parseInt(section.id.split("_")[1]);

        if(section_id > largest_id){
            largest_id = section_id;
        }
    });

    return largest_id + 1;
}

function redirectToEditSection(event){
    if(event.target.classList.contains("more_action_btn") ||
        event.target.classList.contains("more_action_list") ||
        event.target.classList.contains("remove_icon") ||
        event.target.classList.contains("remove_btn") || 
        event.target.closest("li")){
        return;
    }
    
    location.href = "admin_edit_section.html";
}

function submitAddSectionForm(event){
    event.preventDefault();
    event.stopImmediatePropagation();

    const input_add_section = ux("#input_add_section").html();
    const cloned_section_block = ux(".section_block.hidden").clone();
    const sections = ux(".section_container").html();
    const section_title = ux(cloned_section_block.find(".section_details input")).html();
    const input_field = ux(input_add_section.closest(".input-field"));
    const section_id = document.querySelector(".section_container").children.length;

    ux(cloned_section_block.find(".edit_title_icon").on("click", editSectionTitle));
    ux(cloned_section_block.find(".remove_icon").on("click", removeSectionBlock));
    ux(cloned_section_block.find(".duplicate_icon").on("click", duplicateSection));
    ux(cloned_section_block.find(".section_title").on("blur", disableEditSectionTitle));
    ux(cloned_section_block.find(".more_action_btn").on("click", showMaterializeDropdown));

    if(!input_add_section.value.trim().length){
        input_field.addClass("input_error");
    }
    else{
        input_field.removeClass("input_error");
        ux(cloned_section_block.html()).attr("id", "section_"+section_id);
        ux(cloned_section_block.html()).attr("class", "section_block");
        ux(section_title.html()).attr("value", input_add_section.value);
        ux(section_title.html()).attr("data-tooltip", input_add_section.value);
        if(section_title.html().value.length < 43){
            ux(section_title.html()).attr("data-tooltip", "");
            ux(section_title.html()).attr("class", "section_title");
        }
        
        cloned_section_block.on("click", redirectToEditSection);

        section_title.html().setAttribute("readonly", "");
        sections.appendChild(cloned_section_block.html());
    }

    appearEmptySection();
    document.querySelector("#section_form").reset();
    ux(ux(".group_add_section label").html()).addClass("active");
    initializeMaterializeTooltip();
    sections.scrollTop = sections.scrollHeight;
}

function switchText(event){
    let toggle_switch = event.target;
    let switch_btn = ux(".switch_btn .toggle_text").html();
    let invite_collaborator_btn = ux("#invite_collaborator_btn");
    console.log(toggle_switch)
    
    if(toggle_switch.checked){
        switch_btn.innerText = "Private"
        invite_collaborator_btn.removeClass("hidden");
        ux(toggle_switch).attr("checked", "");
    } 
    else {
        toggle_switch.removeAttribute("checked", "");
        invite_collaborator_btn.addClass("hidden");
        switch_btn.innerText = "Public";
    } 
}

function editSectionTitle(event){
    event.stopImmediatePropagation();

    const edit_btn = event.target;
    const section_blk = ux(edit_btn.closest(".section_block"));
    const section_title = ux(section_blk.find(".section_title")).html();
    const end = section_title.html().value.length;

    section_title.html().removeAttribute("readonly");
    section_title.html().setSelectionRange(end, end);
    setTimeout(() => {
        section_title.html().focus();
    }, 0);
}

function setRemoveSectionBlock(event) {
    let remove_modal = document.querySelector("#confirm_to_remove");
    var instance = M.Modal.getInstance(remove_modal);
    instance.open();
    
    const section    = event.target;
    const section_id = section.getAttribute("data-document_id");

    document.getElementById("remove_section_id").value = section_id;
}

function removeSectionBlock(event){
    /* This is just for clickable prototype. Will replace all when form is submitted to the backend */
    const section_id = document.getElementById("remove_section_id").value;

    ux(`#section_${section_id}`).html().className += " animate__animated animate__fadeOut";
    ux(`#section_${section_id}`).html().addEventListener("animationend", () => {
        ux(`#section_${section_id}`).html().remove();
    });

    appearEmptySection();
}

function disableEditSectionTitle(event){
    let section_title = event.target;

    if(section_title.value.trim().length > 38){
        ux(section_title).attr("data-tooltip", section_title.value);
        ux(section_title).attr("class", "section_title tooltipped");
        
        initializeMaterializeTooltip();
    }else{
        ux(section_title).attr("class", "section_title");
        section_title.removeAttribute("data-tooltip", "");
    }

    section_title.setAttribute("readonly", "");
}

function duplicateSection(event){
    event.stopImmediatePropagation();

    let new_section_id = getNewSectionId();
    let source = event.target.closest(".section_block");
    let cloned = ux(source).clone();
    
    cloned.html().setAttribute("id", `section_${new_section_id}`);
    cloned.find(".remove_btn").html().dataset.document_id = new_section_id;

    let cloned_title = ux(cloned.find(".section_title")).html();
    let cloned_list = ux(cloned.find(".dropdown-content")).html();
    let cloned_target = ux(cloned.find(".more_action_btn")).html();
    let source_id = source.getAttribute("id");

    console.log(cloned_title.html().value);
    cloned_title.html().value = `Copy of ${cloned_title.html().value}`;
    ux(cloned.find(".edit_title_icon").on("click", editSectionTitle));
    cloned_target.html().setAttribute("data-target", `section_more_actions_${new_section_id}`);
    cloned_list.html().setAttribute("id", `section_more_actions_${new_section_id}`);
    cloned_list.html().setAttribute("style", "");

    ux(cloned.find(".duplicate_icon").on("click", duplicateSection));
    ux(cloned.find(".remove_icon").on("click", setRemoveSectionBlock));
    ux(cloned.find(".more_action_btn").on("click", showMaterializeDropdown));


    cloned.html().className += " animate__animated animate__zoomIn";
    cloned.html().addEventListener("animationend", () => {
        cloned.html().classList.remove("animate__animated", "animate__zoomIn");
    });

    source.insertAdjacentElement("afterend", cloned.html());
    /* Initializing the dropdown menu. */
    M.Dropdown.init(ux(cloned.html()).find(".dropdown-trigger").html());

    cloned.on("click", redirectToEditSection);

    if(cloned_title.html().value.trim().length > 38){
        ux(cloned_title.html()).attr("data-tooltip", cloned_title.html().value);
        ux(cloned_title.html()).attr("class", "section_title tooltipped");
        ux(cloned_title.html()).attr("readonly", "");
        initializeMaterializeTooltip();
    }
}

function initializeMaterializeTooltip(){
    const elems = document.querySelectorAll('.tooltipped');
    M.Tooltip.init(elems, {
        position: "top"
    });
}

function appearEmptySection(){
    let section_count = ux(".section_container").html().children.length;

    if(section_count <= 1){
        ux(".no_sections").removeClass("hidden");
    }else{
        ux(".no_sections").addClass("hidden");
    }
}

function initializeMaterializeDropdown(){
    const dropdown_elements = document.querySelectorAll('.dropdown-trigger');
    
    if(dropdown_elements){
        M.Dropdown.init(dropdown_elements, {
            coverTrigger: false
        });
    }
}

function showMaterializeDropdown(event){
    event.stopImmediatePropagation();
    const dropdown_content = event.target.closest(".section_controls").querySelector(".dropdown-trigger");
    const instance = M.Dropdown.getInstance(dropdown_content);
    instance.open();
}

function sort_sections(event){
    let sort_by = ux(event.target).attr("data-sort-by");
    let section_lists = document.getElementById('section_container');
    let section_list_nodes = section_lists.childNodes;
    let section_lists_to_sort = [];

    for (let i in section_list_nodes) {
        (section_list_nodes[i].nodeType == 1) && section_lists_to_sort.push(section_list_nodes[i]);
    }
    
    section_lists_to_sort.sort(function(a, b) {
        return a.innerHTML == b.innerHTML ? 0 : ( sort_by === "az" ? (a.innerHTML > b.innerHTML ? 1 : -1) : (b.innerHTML > a.innerHTML ? 1 : -1) );
    });

    for (let i = 0; i < section_lists_to_sort.length; ++i) {
        section_lists.appendChild(section_lists_to_sort[i]);
    }
}