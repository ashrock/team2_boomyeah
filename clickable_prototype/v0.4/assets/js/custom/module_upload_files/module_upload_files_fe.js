(function(){
    document.addEventListener("DOMContentLoaded", async () => {
        ux("#upload_file_section")
            .on("click", ".fetch_files_btn", (event) => {
                let is_expanded = event.target.classList.contains("open");
                ux(".fetch_files_btn").conditionalClass("open", !is_expanded);
          
            })
            .on("click", "#file_upload_btn", (event) => {
                let file_input = event.target.previousElementSibling;
                file_input.click();
            })
            .on("click", ".copy_link_icon", copiedFileLink)
            .on("change", "#file_upload_contents", submitSelectedFiles)
            .on("click", ".delete_icon", promptConfirmRemoveModal)
            ;
    });

    function promptConfirmRemoveModal(event){
        event.preventDefault();
        let remove_uploaded_file_btn   = event.target;
        let file_name                  = ux(remove_uploaded_file_btn).data("file_name");
        let file_id                    = ux(remove_uploaded_file_btn).data("file_id");
        let remove_uploaded_file_modal = ux("#confirm_remove_uploaded_file_modal");
        let modal_instance             = M.Modal.getInstance(remove_uploaded_file_modal);
        let file_is_used               = ux(remove_uploaded_file_btn).data("file_is_used");

        remove_uploaded_file_modal.find(".file_id").val(file_id);
        remove_uploaded_file_modal.find(".file_name").val(file_name);

        if(file_is_used === "1"){
            remove_uploaded_file_modal.find("p").text(`Are you sure you want to remove "${file_name}"? Other tabs are using this file.`);
        }
        else{
            remove_uploaded_file_modal.find("p").text(`Are you sure you want to remove "${file_name}"?`);
        }
    
        modal_instance.open();
    }

    function copiedFileLink(event){
        event.preventDefault();
        let actions_list = ux(event.target.closest(".actions_list"));
        let tooltip_hover = actions_list.find(".tooltip_hover");
        let link_input = actions_list.find("[type=hidden]").val();
        
        tooltip_hover.text("Copied!");
        tooltip_hover.addClass("copied_link");

        navigator.clipboard.writeText(link_input);
        setTimeout(() => {
            addAnimation(tooltip_hover.html(), "animate__fadeOut");
            setTimeout(() => {
                tooltip_hover.text("Copy Link");
                tooltip_hover.removeClass("copied_link");
            }, 500)
        }, 1000);
    }

    function submitSelectedFiles(event) {
        event.preventDefault();
        let files              = event.target.files;
        let max_file_size      = 25 * 1024 * 1024; // 25 MB in bytes
        let allowed_file_types = [
            'image/jpeg', 
            'image/png', 
            'image/gif',
            'image/svg+xml',
            'image/bmp',
            'application/pdf', 
            'application/msword', 
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
            'application/vnd.ms-excel', 
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
            'application/vnd.ms-powerpoint', 
            'application/vnd.openxmlformats-officedocument.presentationml.presentation'
        ];
          
        let invalid_files              = [];
        let invalid_file_type_msg      = "";
        let invalid_file_size_msg      = "";
        let invalid_file_type_size_msg = "";
      
        let error_uploaded_file_modal = ux("#error_uploaded_file_modal");
        let error_title               = error_uploaded_file_modal.find(".error_title");
        let error_file_name           = error_uploaded_file_modal.find(".error_file_name");
        let modal_instance            = M.Modal.getInstance(error_uploaded_file_modal);
      
        Array.from(files).forEach((file) => {
            if(file.size > max_file_size && !allowed_file_types.includes(file.type)){
                invalid_files.push(file.name);
                invalid_file_type_size_msg = "This file exceeds max limit of 25mb & is unsupported file format.";
            }
            else if (file.size > max_file_size) {
                invalid_files.push(file.name);
                invalid_file_size_msg = "This file exceeds max limit of 25mb.";
            }
            else if (!allowed_file_types.includes(file.type)) {
                invalid_files.push(file.name);
                invalid_file_type_msg = "This is an unsupported file format.";
            }
        });
        
        if (invalid_files.length > 0) {
            let error_msg = invalid_file_type_size_msg || invalid_file_type_msg || invalid_file_size_msg;
            error_title.text(error_msg);
            error_file_name.text(invalid_files.join(", "));
            modal_instance.open();
            event.target.value = ''; 
            return;
        }   
        else if(!invalid_files.length && files.length){
            // Submit the form
            let upload_files_form = ux(event.target.closest("#upload_files_form"));
            let upload_files_btn  = upload_files_form.find("#file_upload_btn");
            upload_files_form.trigger("submit");
            
            upload_files_btn.addClass("uploading_event");
            upload_files_btn.text("Uploading")
            upload_files_btn.self().disabled = true;
            
            setTimeout(() => {
                upload_files_btn.removeClass("uploading_event");
                upload_files_btn.text("Upload Files");
                upload_files_btn.self().disabled = false;
            }, 2000);
        }
    }
      
    
})();