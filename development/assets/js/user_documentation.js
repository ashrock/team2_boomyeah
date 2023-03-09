document.addEventListener("DOMContentLoaded", async () => {
    $(".document_block").on("click", function(event){
        let document_id = event.target.id.split("_")[1];

        if(document_id){
            location.href = `/docs/${document_id}`;
        }
    });
});