document.addEventListener("DOMContentLoaded", () => {
    ux("body")
        .on("submit", "#section_form", onSubmitAddSectionForm)
        .on("submit", "#update_section_form", onSubmitUpdateSectionForm)
        .on("submit", "#duplicate_section_form", onSubmitDuplicateSectionForm)
        .on("submit", "#remove_section_form", onSubmitRemoveSectionForm)
        .on("submit", "#change_document_privacy_form", onSubmitChangePrivacy)
        .on("submit", "#reorder_sections_form", submitReorderSections)
        .on("submit", "#udpate_documentation_form", submitUpdateDocumentationData)
        ;
});

function submitUpdateDocumentationData(event){
    event.preventDefault();
    let post_form = ux(event.target);

    ux().post(post_form.attr("action"), post_form.serialize(), (response_data) => {
        if(!response_data.status){
            alert("An error occured while updating documentation!");
        } else {
            if(post_form.find(".update_type").val() == "is_private"){
                let invite_collaborator_btn = ux("#invite_collaborator_btn");
                let is_private = parseInt(post_form.find(".update_value").val());
                let switch_btn = ux(".switch_btn .toggle_text").self();
    
                switch_btn.innerText = (is_private) ? "Private" : "Public";
                invite_collaborator_btn.conditionalClass("hidden", !is_private);
            } else {
                addAnimation(`#document_description`, "animated_blinkBorder");
            }
        }
    }, "json");

    return false;
}

function submitReorderSections(event){
    event.preventDefault();
    let reorder_form = ux(event.target);

    ux().post(reorder_form.attr("action"), reorder_form.serialize(), (response_data) => {
        if(!response_data.status){
            alert("An error occured while reordering sections!");
        }
    }, "json");

    return false;
}

function onSubmitChangePrivacy(event){
    event.stopImmediatePropagation();
    event.preventDefault();
    let post_form = ux(event.target);
    
    /** Use AJAX to change documentation privacy */
    ux().post(post_form.attr("action"), post_form.serialize(), (post_data) => {
        if(post_data.status){
            
        }

        post_form.self().reset();
    }, "json");

    return false;
}

function onSubmitRemoveSectionForm(event){
    event.preventDefault();
    let post_form = ux(event.target);
    
    ux().post(post_form.attr("action"), post_form.serialize(), async (response_data) => {
        if(response_data.status){
            let section_id = response_data.result.section_id;
            let section_block = ux(`#section_${section_id}`);
            section_block.addClass("animate__animated animate__fadeOut");
            section_block.on("animationend", () => {
                section_block.remove();
                appearEmptySection();
            });
        }
    }, "json");

    return false;
}

function onSubmitDuplicateSectionForm(event){
    event.preventDefault();
    let post_form = ux(event.target);
    let section_id = post_form.find(".section_id").val();
    
    ux().post(post_form.attr("action"), post_form.serialize(), async (response_data) => {
        if(response_data.status){
            let duplicate_section_id = response_data.result.section_id;
            await ux(`#section_${section_id}`).after(response_data.result.html);

            addAnimation(`#section_${duplicate_section_id}`, "animate__fadeIn animate__slower");
            initializeMaterializeDropdown(ux(`#section_${duplicate_section_id}`).find(".dropdown-trigger").self());
        }
    }, "json");

    return false;
}

function onSubmitUpdateSectionForm(event){
    event.preventDefault();
    let post_form = ux(event.target);
    let section_id = post_form.find(".section_id").val();
    
    ux().post(post_form.attr("action"), post_form.serialize(), async (response_data) => {
        if(response_data.status){
            addAnimation(`#section_${section_id}`, "animated_blinkBorder");
            initializeMaterializeDropdown(ux(`#section_${section_id}`).find(".dropdown-trigger").self());
        }
    }, "json");

    return false;
}

function onSubmitAddSectionForm(event){
    event.preventDefault();
    let post_form = ux(event.target);
    let section_title = post_form.find(".section_title").val();
    post_form.find(".group_add_section").removeClass("input_error");

    if(section_title){
        ux().post(post_form.attr("action"), post_form.serialize(), async (response_data) => {
            if(response_data.status){
                let section_block = await ux("#section_container").append(response_data.result.html);
                initializeMaterializeDropdown(section_block.find(".dropdown-trigger").self());
                addAnimation(`#section_${response_data.result.section_id}`, "animate__fadeIn animate__slower");
                appearEmptySection();

                post_form.self().reset();
                ux("#input_add_section").self().blur();
                await ux(`#section_${response_data.result.section_id}`).self().scrollIntoView();
                
                setTimeout(() => {
                    addAnimation(`#section_${response_data.result.section_id}`, "animated_blinkBorder animate__slower");
                }, 180);
            } else {
                post_form.find(".group_add_section").addClass("input_error");
            }
        }, "json");
    }
    else{
        post_form.find(".group_add_section").addClass("input_error");
        addAnimation(".group_add_section", "animate__animated animate__headShake");
    }
    
    return false;
}