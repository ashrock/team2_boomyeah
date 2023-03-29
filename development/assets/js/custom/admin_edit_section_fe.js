(
function(){
    const current_location = window.location.pathname;
    const view_path = current_location.substring(0, current_location.lastIndexOf('/'));
    let relative_view_paths = view_path.split("views");
    const relative_view_path = relative_view_paths[0] + "views";
    const relative_assets_path = relative_view_paths[0];
    let global_path = (view_path === "/views")? "." : "..";
    let assets_path = (view_path === "/views" )? ".." : "../..";
    let toast_timeout = null;
    let saving_timeout = null;
    let target_index = 0;
    let load_asset_path = (window.navigator.platform.indexOf("Win") > -1 ) ? assets_path : relative_assets_path;

    document.addEventListener("DOMContentLoaded", async ()=> {
        await include("#main_navigation" , `${relative_view_path}/global/main_navigation.html`, `${load_asset_path}/assets/js/main_navigation.js`);
        await include("#clone_section_page" , `${relative_view_path}/global/clone_section_page.html`);
        await include("#modals_container" , `${relative_view_path}/global/confirm_action_modals.html`);

        if(ux("#add_page_tabs_btn").html()){
            ux("#add_page_tabs_btn").on("click", addNewSectionContent);
            initializeRedactor("#section_pages .tab_content");
        }
        else{
            ux("#prev_page_btn").on("click", ()=> { openSectionTab(-1) })
            ux("#next_page_btn").on("click", ()=> { openSectionTab(1) })
            updateSectionProgress();
        }

        setTimeout(() => {
            ux("#remove_tab_form").on("submit", onConfirmRemoveTab);
        });

        let modals = document.querySelectorAll('.modal');
        M.Modal.init(modals);
        initializeSectionPageEvents();
        
        window.addEventListener("resize", () => {
            if(MOBILE_WIDTH < document.documentElement.clientWidth){
                window.location.reload();
            }
        })
    });

    function updateSectionProgress(){
        let sections = ux("#section_pages").findAll(".section_page_content");
        let section_items = Array.from(sections);
        let total_progress = `${ Math.round(((target_index + 1) / section_items.length) * 100)}%`;
        ux("#section_page_progress .progress").html().style.width = total_progress;
    }

    function initializeSectionPageEvents(ux_target = null, callback = null){
        if(ux_target){
            ux_target.find(".section_page_tabs .add_page_btn").on("click", addNewTab);
            ux_target.find(".section_page_tabs .remove_tab_btn").on("click", showConfirmaRemoveTab);
            bindOpenTabLink(ux_target);
    
            ux_target.findAll((".tab_title")).forEach((tab_title) => {
                ux(tab_title).on("keyup", (event) => {
                    onUpdateTabTitle(event);
                });
            });
        }
        else{
            ux(".section_page_tabs .add_page_btn").onEach("click", addNewTab);
            ux(".section_page_tabs .remove_tab_btn").onEach("click", showConfirmaRemoveTab);
            ux(".section_page_content .tab_title").onEach("keyup", (event) => {
                onUpdateTabTitle(event);
            });
            bindOpenTabLink();
        }
    
        if(callback){
            callback();
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
                
                if(section_items[target_index]){
                    await ux(section).removeClass("active");
                    section_items[target_index].classList.add("active");
                    
                    let animate_direction = (move_index > 0) ? "animate__slideInRight" : "animate__slideInLeft";
                    addAnimation(section_items[target_index], animate_direction, 180);
                }

                if(target_index == FIRST_ITEM){
                    ux("#prev_page_btn").addClass("hidden");
                }
                
                if(target_index == section_items.length - 1){
                    ux("#next_page_btn").addClass("hidden");
                }

                updateSectionProgress();
            }
        });
    }

    function updateSectionProgress(){
        let sections = ux("#section_pages").findAll(".section_page_content");
        let section_items = Array.from(sections);
        let total_progress = `${ Math.round(((target_index + 1) / section_items.length) * 100)}%`;
        ux("#section_page_progress .progress").html().style.width = total_progress;
    }
    
    function onUpdateTabTitle(event){
        let tab_title = event.target;
        let section_page_tab = ux(tab_title.closest(".section_page_tab"));
        let tab_id = section_page_tab.attr("id");
        let tab_title_value = (tab_title.value.length > 0) ? tab_title.value : "Untitled*";
        ux(`.page_tab_item[data-tab_id="${tab_id}"] a`).text(tab_title_value);
        
        if(tab_title.value.length > 0){
            saveTabChanges(section_page_tab);
        }
    }
    
    function bindOpenTabLink(ux_target = null){
        if(ux_target){
            /** For dynamically added sections */
            ux_target.findAll((".section_page_tabs .page_tab_item")).forEach((page_tab_link) => {
                ux(page_tab_link).on("click", (link_event) => {
                    openTabLink(link_event);
                });
            });

            ux_target.findAll((".section_page_tab .tab_title")).forEach((page_tab_link) => {
                ux(page_tab_link).on("click", (link_event) => {
                    openTabLink(link_event, true);
                });
            })
        } else {
            ux(".section_page_tabs .page_tab_item").onEach("click", (event) =>{
                openTabLink(event);
            });
            
            ux(".section_page_tab .tab_title").onEach("click", (event) =>{
                openTabLink(event, true);
            });
        }
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
        addAnimation(active_tab.html(), "animate__fadeIn");
        
        if(active_tab && active_tab.find("input.tab_title").html()){
            active_tab.find("input.tab_title").html().select();
        }
    }
    
    function saveTabChanges(section_page_tab){
        clearTimeout(saving_timeout);
        M.Toast.dismissAll();
        
        saving_timeout = setTimeout(() => {        
            clearTimeout(toast_timeout);
            section_page_tab.find(".saving_indicator").addClass("show");
        
            toast_timeout = setTimeout(() => {
                section_page_tab.find(".saving_indicator").removeClass("show");
                M.toast({
                    html: "Changes Saved",
                    displayLength: 2800,
                });
                
            }, 800);
        }, 480);
    }
    
    function addNewSectionContent(event){
        event.preventDefault();
        let tab_id = `tab_${ new Date().getTime()}`;
        let section_pages = ux("#section_pages");
        let section_page_content = ux("#clone_section_page .section_page_content").clone();
        let section_page_tab = section_page_content.find(".section_page_tab");
        section_page_content.find(".page_tab_item").addClass("active");
        section_page_tab.addClass("show");
        section_pages.html().append(section_page_content.html());
        section_page_tab.html().id = tab_id;
        section_page_content.find(".section_page_tab .tab_title").html().select();
        section_page_content.find(".section_page_tabs .page_tab_item").html()
            .setAttribute("data-tab_id", tab_id);
        section_page_tab.find(".checkbox_label").attr("for", "allow_comments_"+ tab_id);
        section_page_tab.find("input[type=checkbox]").attr("id", "allow_comments_"+ tab_id);
        addAnimation(section_page_content.html(), "animate__zoomIn");

        /** Rebind Event Listeners */
        initializeSectionPageEvents(section_page_content);
        initializeRedactor(`#${tab_id} .tab_content`);

        /* Scroll to bottom */
        window.scrollTo(0, document.body.scrollHeight);
    }
    
    function addNewTab(event){
        event.preventDefault();
        let tab_item = event.target;
        let add_page_tab = event.target.closest(".add_page_tab");
        let section_page_content = ux(tab_item.closest(".section_page_content"));
        let section_page_tabs_list = ux(tab_item.closest(".section_page_tabs"));
        let page_tab_clone = ux("#clone_section_page .section_page_tab").clone();
        let page_tab_item = ux("#clone_section_page .page_tab_item").clone();
        let tab_id = `tab_${ new Date().getTime()}`;
        page_tab_clone.html().id = tab_id;
        section_page_content.html().append(page_tab_clone.html());
        
        page_tab_clone.find(".checkbox_label").attr("for", "allow_comments_"+ tab_id);
        page_tab_clone.find("input[type=checkbox]").attr("id", "allow_comments_"+ tab_id);
        
        /** Insert New tab */
        section_page_tabs_list.html().append(page_tab_item.html());
        addAnimation(page_tab_item.html(), "animate__zoomIn");

        /** Insert Add page tab btn at the end */
        section_page_tabs_list.html().append(add_page_tab);
        page_tab_item.html().setAttribute("data-tab_id", tab_id);
        
        /** Rebind tab-related events */
        page_tab_item.find(".remove_tab_btn").on("click", showConfirmaRemoveTab);
        page_tab_clone.find(".tab_title").on("keyup", (event) => {
            onUpdateTabTitle(event, section_page_tabs_list.findAll(".page_tab_item").length);
        });
        bindOpenTabLink(section_page_content);
        
        setTimeout(() => {
            initializeRedactor(`#${tab_id} .tab_content`);
            
            /** Auto click new tab */            
            page_tab_item.html().click();
        });
    }

    function showConfirmaRemoveTab(event){
        event.stopImmediatePropagation();
        let remove_tab_btn = event.target;
        let tab_item = remove_tab_btn.closest(".page_tab_item");
        let tab_title = tab_item.innerText.substring(0, tab_item.innerText.length - 1);
        let tab_id = tab_item.getAttribute("data-tab_id");

        let remove_tab_form = ux("#remove_tab_form");
        remove_tab_form.find(".tab_id").html().value = tab_id.replace("tab_", "");
        
        let remove_tab_modal = ux("#confirm_remove_tab_modal");
        remove_tab_modal.find(".tab_title").text(tab_title.trim());
        let modal_instance = M.Modal.getInstance(remove_tab_modal);
        modal_instance.open();
    }

    function onConfirmRemoveTab(event){
        event.stopImmediatePropagation();
        event.preventDefault();
        
        let raw_form_data = new FormData(event.target);
        let post_data = new Object();
        
        /** Simulate Form Submission */
        setTimeout(() => {
            for (const [key, value] of raw_form_data) {
                console.log(`${key}: ${value}`);
                post_data[key] = value;
            }
            
            /** Do these after form submission */
            let tab_item = ux(`.page_tab_item[data-tab_id="tab_${post_data.tab_id}"]`);
            removeSectionTab(tab_item.html());
        }, 148);

        return false;
    }
    
    function removeSectionTab(tab_item){
        let section_page_content = tab_item.closest(".section_page_content");
        let section_page_tabs = tab_item.closest(".section_page_tabs");
        let tab_id = tab_item.getAttribute("data-tab_id");
        
        addAnimation(tab_item, "animate__fadeOut");
        addAnimation(ux(`#${tab_id}`).html(), "animate__fadeOut");

        setTimeout(() => {
            ux(`#${tab_id}`).html().remove();
            tab_item.remove();
            
            setTimeout(() => {
                if(ux(section_page_tabs).findAll(".page_tab_item").length === 0){
                    section_page_content.remove();
                }else{
                    ux(section_page_tabs).findAll(".page_tab_item")[0].click();
                }
            });
        }, 148);
    }
    
    function initializeRedactor(selector){
        RedactorX(selector, {
            editor: {
                minHeight: '360px'
            }
        });

        if(typeof Sortable !== "undefined"){
            document.querySelectorAll(".section_page_tabs").forEach((section_tabs_list) => {
                Sortable.create(section_tabs_list, {
                    filter: ".add_page_tab"
                });
            });
        }
    }
})();


function printPageTabs(section_pages){
    let user_view_section_html = '';

    let temp_counter = 0;
    let counter = 0;
    for(let page_key in section_pages){
        user_view_section_html += `
        <div class="section_page_content${ counter === 0 ? ' active' : '' }">
            <ul class="section_page_tabs">`
        section_pages[page_key].forEach((section_page, index) => {
            user_view_section_html += `
                <li class="page_tab_item${ index === 0 ? ' active' : '' }" data-tab_id="tab_${counter + (index + 1)}">
                    <a href="#tab_${counter + (index + 1)}">${ section_page.tab_title }</a>
                </li>
            `;
        });

        user_view_section_html += `</ul>`;

        section_pages[page_key].forEach((section_page, index) => {
            user_view_section_html += `
            <div class="section_page_tab${ index === 0 ? ' show' : '' }" id="tab_${counter + (index + 1)}">
                <h3 class="tab_title">${section_page.tab_title}</h3>
                <p id="tab_content_${counter + (index + 1)}" class="tab_content">${section_page.tab_description}</p>
                <a href="#" data-target="mobile_comments_slideout" class="show_comments_btn sidenav-trigger">Comments (${section_page.comments.length})</a>
                <div class="tab_comments comment_container">
                    <form action="/" method="POST" class="add_comment_form">
                        <div class="comment_field">
                            <div class="comment_message_content input-field col s12">
                                <label for="post_comment_${counter + (index + 1)}">Write a comment</label>
                                <textarea name="post_comment" id="post_comment_${counter + (index + 1)}" class="materialize-textarea comment_message"></textarea>
                            </div>
                        </div>
                    </form>
                    <ul id="user_view_comments" class="comments_list"></ul>
                </div>
            </div>
            `;

            temp_counter += index + 1;
        });

        user_view_section_html += `</div>`;
        counter = temp_counter;
    }
}