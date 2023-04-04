(function(){
    document.addEventListener("DOMContentLoaded", (ux_event) => {    
        /* Add non-link/non-button/non-input elements with brute force events here */
        const INTERACTIVE_IDS = {};
        const INTERACTIVE_CLASSES = {
            "document_block": "click",
            "section_block": "click"
        }
    
        let current_focused_element = null;
        document.addEventListener("keyup", (event) => {
            if(event.key == KEYS.ENTER_KEY && current_focused_element){
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
    
            if(event.key == KEYS.TAB_KEY){
                current_focused_element = event.target;
                return;
            }

            current_focused_element = null;
        })
    });
})()