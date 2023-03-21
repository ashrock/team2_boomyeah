(function(){
    document.addEventListener("DOMContentLoaded", async ()=> {
        ux("#upload_file_section")
        .on("click", ".fetch_files_btn", toggleFilesView)
        .on("click", "#file_upload_btn", (event)=>{
            let file_input = event.target.previousElementSibling;
            file_input.click();
        })
        .on("click", ".copy_link_icon", copiedFileLink);
    });

    function toggleFilesView(event){
       let is_expanded = event.target.classList.contains("open");
       ux(".fetch_files_btn").conditionalClass("open", !is_expanded);
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
            setTimeout(() =>{
                tooltip_hover.text("Copy Link");
                tooltip_hover.removeClass("copied_link");
            }, 500)
        }, 1000);
    }
})();