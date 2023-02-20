let sidenav_elems = document.querySelectorAll('.sidenav');
let sidenav_instances = M.Sidenav.init(sidenav_elems);
let select = document.querySelectorAll('select');
let dropdown = M.FormSelect.init(select);

ux("#search_documentation_field").on("keydown", onSearchDocumentation);
ux("#search_section_field").on("keyup", onSearchSection);

let search_timeout = null;
function onSearchDocumentation(event){
    clearTimeout(search_timeout);
    let search_field = event.target;
    let search_value = search_field.value.toLowerCase();
    
    search_timeout = setTimeout(() => {
        onSearchViewItem(ux("#documentations"), search_value, "innerText", ".document_block", ".document_block .document_details h2");
    }, 480);
}

function onSearchSection(event){
    clearTimeout(search_timeout);
    let search_field = event.target;
    let search_value = search_field.value.toLowerCase();

    search_timeout = setTimeout(() => {
        onSearchViewItem(ux("#section_container"), search_value, "value", ".section_block", ".section_block .section_details .section_title")
    }, 480);
}

function onSearchViewItem(target_container, search_value = "", matching_field = "innerText", target_element, target_item){
    target_container.findAll(target_element).forEach((section_block) => {
        ux(section_block).removeClass("hidden");
    });
    
    let matched_elements = [];
    if(search_value.trim().length){
        target_container.findAll(target_item).forEach((search_element) => {
            let matching_value = search_element[matching_field].toLowerCase();
            let section_block = search_element.closest(target_element);
            ux(section_block).addClass("hidden");

            if(matching_value.indexOf(search_value) > -1){
                matched_elements.push(section_block);
            }
        });

        if(matched_elements.length){
            matched_elements.forEach((matched_element) => ux(matched_element).removeClass("hidden"));
            return;
        }
    }
}