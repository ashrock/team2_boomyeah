document.addEventListener("DOMContentLoaded", async () => {
    ux(".section_block").onEach("click", function(event){
        let event_target = event.target;
        
        if(event.target.closest(".section_block")){
            event_target = event.target.closest(".section_block");
        }

        location.href += `/${event_target.id.split("_")[1]}`;
    });

    initializeMaterializeDropdown();
}); 

function initializeMaterializeDropdown(){
    const elems = document.querySelectorAll('.dropdown-trigger');
    M.Dropdown.init(elems, {
        coverTrigger: false
    });
}