async function include(target_element, file_path, script_path = null){
    await fetch(file_path)
        .then(response=> response.text())
        .then(text => {
            let content = document.createElement("div");
            content.innerHTML = text;
            document.querySelector(target_element).append(content);
            content.replaceWith(...content.childNodes);
            
            if(script_path){
                let script_content = document.createElement("script");
                script_content.setAttribute("src", script_path);
                document.querySelector("body").append(script_content);
            }
        });
}

function include_partial(file_path){
    let partial_content = fetch(file_path)
                            .then(response => response.text())
                            .then(parsed_html => {
                                    let content = document.createElement("div");
                                    content.innerHTML = parsed_html;
                                    content.replaceWith(...content.childNodes);
                                    return content;
                                });
    return partial_content;
}