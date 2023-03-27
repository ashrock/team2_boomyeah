let sidenav_elems = document.querySelectorAll('.sidenav');
M.Sidenav.init(sidenav_elems);
let form_select = document.querySelectorAll("select");
M.FormSelect.init(form_select);

let user_dropdown = document.querySelectorAll(".dropdown-action");
M.Dropdown.init(user_dropdown, {
    coverTrigger: false
});

document.addEventListener("DOMContentLoaded", () => {
    ux("body")
        .on("click", ".log_out_btn", (event)=>{
            event.stopImmediatePropagation();
        });
})