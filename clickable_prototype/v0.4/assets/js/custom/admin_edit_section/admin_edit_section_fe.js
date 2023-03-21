(
function(){
    let target_index = 0;
    let keyup_timeout = null;
    let active_scroll_offset = 0;
    let module_scroll_checkpoints = [];

    document.addEventListener("DOMContentLoaded", async ()=> {
        if(!ux("#add_page_tabs_btn").self()){
            ux("#prev_page_btn").on("click", ()=> { openSectionTab(-1) });
            ux("#next_page_btn").on("click", ()=> { openSectionTab(1) });
        }

        ux("body")
            .on("click", ".section_page_tabs .page_tab_item a", (event) =>{
                openTabLink(event);
            })
            .on("click", ".section_page_tabs .remove_tab_btn", showConfirmRemoveTab)
            .on("blur", "#section_short_description", (event) => {
                clearTimeout(keyup_timeout);
                ux("#edit_section_form").trigger("submit");
            })
            .on("keyup", "#section_short_description", (event) => {
                clearTimeout(keyup_timeout);

                keyup_timeout = setTimeout(() => {    
                    ux("#edit_section_form").trigger("submit");
                }, 480);
            })
            .on("keyup", ".section_page_content .tab_title", (event) => {
                onUpdateTabTitle(event);
            })
            .on("change", ".is_comments_allowed", updateIsCommentAllowed)
        
        /** Enable title field clicking only for mobile */
        if( document.documentElement.clientWidth <= MOBILE_WIDTH ){
            ux("body")
                .on("click", ".section_page_tab .tab_title", (event) =>{
                    openTabLink(event, true);
                });
        }
        /**
         * On load adjust the text area size base on its pre-loaded content
         */
        let section_short_description = ux("#section_short_description").self();
        (section_short_description.tagName != "P") && autoExpand(section_short_description);

        /**
         * Add show class to tabs on DOM load
         */
        onLoadShowTab();
        let modals = document.querySelectorAll('.modal');
        M.Modal.init(modals);
        
        window.addEventListener("resize", () => {
            if(MOBILE_WIDTH < document.documentElement.clientWidth){
                window.location.reload();
            }
        });

        ux("#section_pages").findAll(".section_page_content").forEach(module_item => {
            let module_coords = module_item.getBoundingClientRect();
            module_scroll_checkpoints.push((module_coords.top - 56));
        });
        
        window.addEventListener("scroll", async (event) => {
            let scroll_top_offset = document.body.getBoundingClientRect().top;
            await ux("#prev_page_btn").addClass("hidden");
            await ux(".section_page_content.active").removeClass("active");
            let section_pages = ux("#section_pages").findAll(".section_page_content");

            for(scroll_index in module_scroll_checkpoints){
                let scroll_value = (module_scroll_checkpoints[scroll_index] - 64) * -1;

                if(scroll_value > scroll_top_offset){
                    active_scroll_offset = scroll_index;
                }

                if(active_scroll_offset > 0){
                    ux("#prev_page_btn").removeClass("hidden");
                }
            }

            ux(section_pages[active_scroll_offset]).addClass("active");
        });

        window.scrollTo(0, 0);
    });

    window.onbeforeunload = function () {
        window.scrollTo(0, 0);
    }

    function onLoadShowTab(){
        let section_pages = ux("#section_pages").findAll(".section_page_content");
        section_pages.forEach((page) => {
            let show_added = false;
            page.querySelectorAll(".section_page_tab").forEach((section_tab) => {
                if (!show_added && !section_tab.classList.contains("show")) {
                    if ( !Array.from(section_tab.parentNode.children).some( (element_section) => element_section.classList.contains("show")) ) {
                        section_tab.classList.add("show");
                        show_added = true;
                    }
                }
            });
        });
    }

    function updateIsCommentAllowed(event){
        let allow_comments         = event.target.checked;
        let update_module_tab_form =  ux(event.target.closest(".update_module_tab_form"));
        let is_comments_allowed    = update_module_tab_form.find("[name=is_comments_allowed");
        is_comments_allowed.val(allow_comments? 1 : 0);
        update_module_tab_form.trigger("submit");
    }

    function updateSectionProgress(){
        let sections = ux("#section_pages").findAll(".section_page_content");
        let section_items = Array.from(sections);
        let total_progress = `${ Math.round(((target_index + 1) / section_items.length) * 100)}%`;
        ux("#section_page_progress .progress").self().style.width = total_progress;
    }

    function onUpdateTabTitle(event){
        let tab_title = event.target;
        let section_page_tab = ux(tab_title.closest(".section_page_tab"));
        let tab_id = section_page_tab.attr("id");
        let tab_title_value = (tab_title.value.length > 0) ? tab_title.value : "Untitled Tab*";
        ux(`.page_tab_item[data-tab_id="${tab_id}"] a`).text(tab_title_value);
        let tab_title_ux = section_page_tab.find(".tab_title");

        if(tab_title.value.length > 1){
            /* saveTabChanges(section_page_tab); */
            clearTimeout(keyup_timeout);
            tab_title_ux.removeClass("error");
            keyup_timeout = setTimeout(() => {
                ux(tab_title.closest(".update_module_tab_form")).trigger("submit");
            }, 1000);
        }
        else{
            clearTimeout(keyup_timeout);
            tab_title_ux.addClass("error");
        }
    }

    function openSectionTab(move_index){
        let sections = ux("#section_pages").findAll(".section_page_content");
        let section_items = Array.from(sections);
        ux("#prev_page_btn").removeClass("hidden");
        ux("#next_page_btn").removeClass("hidden");

        section_items.forEach(async (section, section_index) => {
            if(section.classList.contains("active")){
                target_index = section_index + move_index;

                if(target_index == FIRST_ITEM){
                    ux("#prev_page_btn").addClass("hidden");
                }
                
                if(target_index == section_items.length - 1){
                    ux("#next_page_btn").addClass("hidden");
                }
                
                if(section_items[target_index]){
                    await ux(section).removeClass("active");
                    section_items[target_index].classList.add("active");
                    window.scrollTo({
                        top: module_scroll_checkpoints[target_index],
                        behavior: "smooth",
                    });
                }
            }
        });
    }

    async function openTabLink(event, is_title = false){
        event.preventDefault();
        event.stopImmediatePropagation();
        
        let tab_item = event.target;
        let section_page_content = ux(tab_item.closest(".section_page_content"));
        let section_page_tabs_list = section_page_content.find(".section_page_tabs");
        let page_tab_item = tab_item.closest(".page_tab_item");
        let tab_id = (!is_title) ? ux(page_tab_item).attr("data-tab_id") : ux(tab_item.closest(".section_page_tab")).attr("id");
    
        await section_page_tabs_list.findAll(".page_tab_item").forEach(element => element.classList.remove("active"));
        await section_page_content.findAll(".section_page_tab").forEach(element => element.classList.remove("show"));
        ux(`.page_tab_item[data-tab_id="${tab_id}"]`).addClass("active");
        
        let active_tab = ux(`#${ tab_id }`).addClass("show");
        addAnimation(active_tab.self(), "animate__fadeIn");
        
        if(active_tab && active_tab.find("input.tab_title").self()){
            active_tab.find("input.tab_title").self().select();
        }
    }

    function showConfirmRemoveTab(event){
        event.stopImmediatePropagation();
        let remove_tab_btn = event.target;
        let tab_item = remove_tab_btn.closest(".page_tab_item");
        let tab_title = tab_item.innerText.substring(0, tab_item.innerText.length - 1);
        let tab_id = ux(tab_item).data("tab_id");
        let module_id = ux(tab_item).data("module_id");
        let remove_tab_form = ux("#remove_tab_form");
        remove_tab_form.find(".tab_id").val( tab_id.replace("tab_", "") );
        remove_tab_form.find(".module_id").val( module_id );
        
        let remove_tab_modal = ux("#confirm_remove_tab_modal");
        remove_tab_modal.find(".tab_title").text(tab_title.trim());
        let modal_instance = M.Modal.getInstance(remove_tab_modal);
        modal_instance.open();
    }
})();