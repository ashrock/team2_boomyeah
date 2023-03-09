let toast_timeout = null;
let saving_timeout = null;

document.addEventListener("DOMContentLoaded", () => {
    if(ux("#add_page_tabs_btn").self()){
        ux("#add_page_tabs_btn").on("click", addNewSectionContent);
        initializeRedactor("#section_pages .tab_content");
    }

    ux("body")
        .on("submit", "#remove_tab_form", onConfirmRemoveTab)
        .on("click", ".section_page_tabs .add_page_btn", addNewTab);
});
    
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
    section_pages.self().append(section_page_content.self());
    section_page_tab.self().id = tab_id;
    section_page_content.find(".section_page_tab .tab_title").self().select();
    section_page_content.find(".section_page_tabs .page_tab_item").self()
        .setAttribute("data-tab_id", tab_id);
    section_page_tab.find(".checkbox_label").attr("for", "allow_comments_"+ tab_id);
    section_page_tab.find("input[type=checkbox]").attr("id", "allow_comments_"+ tab_id);
    addAnimation(section_page_content.self(), "animate__zoomIn");

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
    page_tab_clone.self().id = tab_id;
    section_page_content.self().append(page_tab_clone.self());
    
    page_tab_clone.find(".checkbox_label").attr("for", "allow_comments_"+ tab_id);
    page_tab_clone.find("input[type=checkbox]").attr("id", "allow_comments_"+ tab_id);
    
    /** Insert New tab */
    section_page_tabs_list.self().append(page_tab_item.self());
    addAnimation(page_tab_item.self(), "animate__zoomIn");

    /** Insert Add page tab btn at the end */
    section_page_tabs_list.self().append(add_page_tab);
    page_tab_item.self().setAttribute("data-tab_id", tab_id);
    
    setTimeout(() => {
        initializeRedactor(`#${tab_id} .tab_content`);
        
        /** Auto click new tab */
        page_tab_item.find("a").self().click();
    });
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
        removeSectionTab(tab_item.self());
    }, 148);

    return false;
}
    
function removeSectionTab(tab_item){
    let section_page_content = tab_item.closest(".section_page_content");
    let section_page_tabs = tab_item.closest(".section_page_tabs");
    let tab_id = ux(tab_item).data("tab_id");
    
    addAnimation(tab_item, "animate__fadeOut");
    addAnimation(ux(`#${tab_id}`).self(), "animate__fadeOut");

    setTimeout(() => {
        ux(`#${tab_id}`).self().remove();
        tab_item.remove();
        
        setTimeout(() => {
            if(ux(section_page_tabs).findAll(".page_tab_item a").length === 0){
                section_page_content.remove();
            }else{
                ux(section_page_tabs).findAll(".page_tab_item a")[0].click();
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

function initializeSectionPageEvents(ux_target = null, callback = null){
    if(callback){
        callback();
    }
}