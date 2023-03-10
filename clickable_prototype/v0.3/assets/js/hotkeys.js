document.addEventListener("DOMContentLoaded", () => {
    document.onkeydown = checkShortcut;
});

function checkShortcut(e){
    const redirect_path = window.location.pathname.split("/").pop();
    const SHORTCUT_VALUES = {
        "keyCode_49_url": `/views/default_data/${redirect_path}`,
        "keyCode_50_url": `/views/no_data/${redirect_path}`,
        "keyCode_51_url": `/views/small_data/${redirect_path}`,
        "keyCode_52_url": `/views/medium_data/${redirect_path}`,
        "keyCode_53_url": `/views/large_data/${redirect_path}`
    }

    let shortcut_event = window.event? event : e;

    if(shortcut_event.altKey){
        let get_redirect_url = SHORTCUT_VALUES[`keyCode_${shortcut_event.keyCode}_url`];

        if(get_redirect_url){
            window.location.replace(`${document.location.origin}${get_redirect_url}`);
        }
    }
}