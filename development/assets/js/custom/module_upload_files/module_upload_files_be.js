document.addEventListener("DOMContentLoaded", async () => {
    ux("body")
        .on("submit", "#upload_files_form", onUploadFiles)
        .on("submit", "#remove_uploaded_file_form", onConfirmRemoveFile)
        ;
});

function onConfirmRemoveFile(event){
    event.stopImmediatePropagation();
    event.preventDefault();

    let remove_file_form = ux(event.target);

    ux().post(remove_file_form.attr("action"), remove_file_form.serialize(), async (response_data) => {
        if(response_data.status){
            let file_id       = response_data.result.file_id;
            let uploaded_file = ux(`.file_${file_id}`).self();
            let file_counter  = ux("#files_counter");
            let counter       = parseInt(file_counter.data("files_count"));
            counter -= 1;

            addAnimation(uploaded_file, "animate__fadeOut");
            file_counter.attr("data-files_count", counter);
            file_counter.text(`(${counter})`);

            if(!counter){
                ux("#files_list").html(response_data.result.html);
            }
         
            setTimeout(() => {
                uploaded_file.remove();
            }, 480);
        }
    }, "json"); 
    return false;
}

function onUploadFiles(event){
    event.stopImmediatePropagation();
    event.preventDefault();
    let upload_files_section = ux(event.target.closest("#upload_file_section"));
    let upload_files_form = upload_files_section.find("#upload_files_form");

    ux().post(upload_files_form.attr("action"), upload_files_form.serialize(), async (response_data) => {
        if(response_data.status){
            let uploaded_files_html = response_data.result.html; // upload_section_items_partial.php
            let files_counter       = parseInt(response_data.result.files_uploaded);
            let files_counter_text  = upload_files_section.find("#files_counter");
            let files_list          = upload_files_section.find("#files_list");

            parseInt(files_counter_text.data("files_count")) ? files_list.append(uploaded_files_html) : files_list.html(uploaded_files_html);
            
            // Update files count
            files_counter = files_counter + parseInt(files_counter_text.data("files_count"));
            files_counter_text.attr("data-files_count", files_counter);
            files_counter_text.text(`(${files_counter})`);
            files_counter_text.self().hidden = false;
        }
    }, "json");
    return false;
}