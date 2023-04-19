(function(){
    document.addEventListener("DOMContentLoaded", (ux_event) => {    
        /* Add non-link/non-button/non-input elements with brute force events here */
        const INTERACTIVE_IDS = {};
        const INTERACTIVE_CLASSES = {
            "document_block": "click",
            "section_block": "click",
            "page_tab_item_title": "click",
            "remove_tab_btn": "click",
            "checkbox_marker": "click",
        }
    
        let current_focused_element = null;
        document.addEventListener("keydown", (event) => {
            let open_modal = ux("body").findAll(".confirmation_modal.open");

            if(event.key == KEYS.ENTER_KEY){
                if(current_focused_element){
                    let current_id = current_focused_element.id;
                    let current_classes = current_focused_element.classList;
                    
                    if(INTERACTIVE_IDS.hasOwnProperty(current_id)){
                        ux(current_focused_element).trigger(INTERACTIVE_IDS[current_id]);
                        current_focused_element = null;
                        return;
                    }
                    
                    current_classes.forEach(current_class => {
                        if(INTERACTIVE_CLASSES.hasOwnProperty(current_class)){
                            ux(current_focused_element).trigger(INTERACTIVE_CLASSES[current_class]);
                            current_focused_element = null;
                            return;
                        }
                    });
                }
                
                if(open_modal.length){
                    let yes_btn = ux(open_modal[0]).find(".yes_btn");
                    yes_btn.self().click();
                    return;
                }
            }
            
            if(event.key == KEYS.ESCAPE_KEY){
                event.stopImmediatePropagation();
                event.preventDefault();
                let no_btn = ux(open_modal[0]).find(".no_btn");
                no_btn.self().click();
                return;
            }
    
            if(event.key == KEYS.TAB_KEY){
                current_focused_element = event.target;
                return;
            }

            current_focused_element = null;
        })
    });
})()