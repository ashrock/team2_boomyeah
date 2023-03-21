document.addEventListener("DOMContentLoaded", async () => {
    ux(".section_block").onEach("click", function(){
        alert("Redirecting to the user view section page will be added in v0.3.");
    });

    ux(".sort_by").onEach("click", sort_documentations);
    initializeMaterializeDropdown();

    let sidenav_elems = document.querySelectorAll('.sidenav');
    let instances = M.Sidenav.init(sidenav_elems);
}); 

function printSectionOutline(section_data){
    const documentation_div = document.getElementById("documentations");
    let section_block = "";

    /* Print all documentation */
    section_data.forEach((section, index) => {
        section_block += `
            <div class="section_block">
                <div class="section_details">
                    <input type="text" name="section_title" value="${section}" id="" class="section_title tooltipped" data-tooltip="${section}">
                </div>
                <div class="section_controls">
                    <span>${ Math.ceil(Math.random() * (20 - 1) + 1) } Tabs</span>
                </div>
            </div>`;
    });
}


function sort_documentations(event){
    let sort_by = ux(event.target).attr("data-sort-by");
    let section_lists = document.getElementById('section_container');
    let section_list_nodes = section_lists.childNodes;
    let section_lists_to_sort = [];

    for (let i in section_list_nodes) {
        (section_list_nodes[i].nodeType == 1) && section_lists_to_sort.push(section_list_nodes[i]);
    }
    
    section_lists_to_sort.sort(function(a, b) {
        return a.innerHTML == b.innerHTML ? 0 : ( sort_by === "az" ? (a.innerHTML > b.innerHTML ? 1 : -1) : (b.innerHTML > a.innerHTML ? 1 : -1) );
    });

    for (let i = 0; i < section_lists_to_sort.length; ++i) {
        section_lists.appendChild(section_lists_to_sort[i]);
    }
}

function initializeMaterializeDropdown(){
    const elems = document.querySelectorAll('.dropdown-trigger');
    M.Dropdown.init(elems, {
        coverTrigger: false
    });
}