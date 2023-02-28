document.addEventListener("DOMContentLoaded", () => {
    ux("body")
        .on("submit", "#section_form", onSubmitAddSectionForm)
        .on("submit", "#update_section_form", onSubmitUpdateSectionForm)
        .on("submit", "#duplicate_section_form", onSubmitDuplicateSectionForm)
        ;
});

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
        } else {

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
            await ux(`#section_${section_id}`).replaceWith(response_data.result.html);
            addAnimation(`#section_${section_id}`, "animated_blinkBorder");
            initializeMaterializeDropdown(ux(`#section_${section_id}`).find(".dropdown-trigger").self());
        } else {

        }
    }, "json");

    return false;
}

function onSubmitAddSectionForm(event){
    event.preventDefault();
    let post_form = ux(event.target);
    let section_title = post_form.find(".section_title").val();

    if(section_title){
        ux().post(post_form.attr("action"), post_form.serialize(), async (response_data) => {
            if(response_data.status){
                let section_block = await ux("#section_container").prepend(response_data.result.html);
                initializeMaterializeDropdown(section_block.find(".dropdown-trigger").self());
            } else {

            }
        }, "json");
    }
    
    return false;
}