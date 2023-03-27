document.addEventListener("DOMContentLoaded", async () => {
    ux(".section_block").onEach("click", function(event){
        location.href += `/${event.target.id.split("_")[1]}`;
    });

    initializeMaterializeDropdown();
}); 

function initializeMaterializeDropdown(){
    const elems = document.querySelectorAll('.dropdown-trigger');
    M.Dropdown.init(elems, {
        coverTrigger: false
    });
}