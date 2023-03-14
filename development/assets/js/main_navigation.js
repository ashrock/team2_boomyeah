let sidenav_elems = document.querySelectorAll('.sidenav');
let sidenav_instances = M.Sidenav.init(sidenav_elems);
let select = document.querySelectorAll('select');
let dropdown = M.FormSelect.init(select);
let search_timeout = null;

const elem = document.querySelectorAll('.dropdown-action');
M.Dropdown.init(elem, {
    coverTrigger: false
});

function onSearchDocumentation(event){
    clearTimeout(search_timeout);
    let search_field = event.target;
    let search_value = search_field.value.toLowerCase();
    
    search_timeout = setTimeout(() => {
        onSearchViewItem($("#documentations"), search_value, "innerText", ".document_block", ".document_block .document_details h2");
    }, 480);
}

function onSearchSection(event){
    clearTimeout(search_timeout);
    let search_field = event.target;
    let search_value = search_field.value.toLowerCase();

    search_timeout = setTimeout(() => {
        onSearchViewItem($("#section_container"), search_value, "value", ".section_block", ".section_block .section_details .section_title")
    }, 480);
}

function onSearchViewItem(target_container, search_value = "", matching_field = "innerText", target_element, target_item){
    target_container.find(target_element).each(() => {
        let section_block = this;
        $(section_block).removeClass("hidden");
    });
    
    let matched_elements = [];
    if(search_value.trim().length){
        target_container.find(target_item).each(() => {
            let search_element = this;
            let matching_value = search_element[matching_field].toLowerCase();
            let section_block = search_element.closest(target_element);
            $(section_block).addClass("hidden");

            if(matching_value.indexOf(search_value) > -1){
                matched_elements.push(section_block);
            }
        });

        if(matched_elements.length){
            matched_elements.forEach((matched_element) => $(matched_element).removeClass("hidden"));
            return;
        }
    }
}