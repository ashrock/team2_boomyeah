
document.addEventListener("DOMContentLoaded", async (event) => {
    let document_element = event.target;

    ux("body")
        .on("click", ".fetch_tab_posts_btn", (event) => {
            event.preventDefault();
            let tab_id = ux(event.target).data("tab_id");
            let fetch_tab_posts_form = ux("#fetch_tab_posts_form");
            fetch_tab_posts_form.find(".tab_id").val(tab_id);
            fetch_tab_posts_form.trigger("submit");
        })
        .on("submit", "#fetch_tab_posts_form", onFetchTabPosts)
        .on("submit", ".add_reply_form", onAddPostComment)

        .on("click", ".toggle_replies_btn b", showRepliesList)
        .on("submit", "#remove_comment_form", onConfirmDeleteComment);
});

function onAddPostComment(event){
    event.stopImmediatePropagation();
    event.preventDefault();
    let post_form = ux(event.target);
    
    ux().post(post_form.attr("action"), post_form.serialize(), async (response_data) => {
        if(response_data.status){
            console.log(response_data)
            // let tab_id = `#tab_${response_data.result.tab_id}`;
            // addAnimation(ux(tab_id).find(".tab_comments .comments_list").self(), "animate__fadeOut");
            // setTimeout(() => {
            //     ux(tab_id).find(".tab_comments .comments_list").html(response_data.result.html);
            // }, 200);
        }
    }, "json");
    
    return false;
}

function onFetchTabPosts(event){
    event.stopImmediatePropagation();
    event.preventDefault();
    let post_form = ux(event.target);
    
    ux().post(post_form.attr("action"), post_form.serialize(), async (response_data) => {
        if(response_data.status){
            let tab_id = `#tab_${response_data.result.tab_id}`;
            addAnimation(ux(tab_id).find(".tab_comments .comments_list").self(), "animate__fadeOut");
            setTimeout(() => {
                ux(tab_id).find(".tab_comments .comments_list").html(response_data.result.html);
            }, 200);
        }
    }, "json");
    
    return false;
}

function showRepliesList(event){
    event.stopImmediatePropagation();
    let show_replies_btn = event.target.closest(".toggle_replies_btn");
    let comment_item = event.target.closest(".comment_item");
    let replies_list  = ux(comment_item).find(".replies_list");
    
    if(!replies_list.self().classList.contains("show")){
        addAnimation(replies_list.self(), "animate__zoomIn");

        replies_list.addClass("show");
        ux(show_replies_btn).addClass("hidden");
    }
}

function onConfirmDeleteComment(event){
    event.stopImmediatePropagation();
    event.preventDefault();

    /** Do these after form submission */
    let comment_container = null;
    
    if(active_comment_item){
        if(active_comment_item.closest(".replies_list")){
            comment_container = active_comment_item.closest(".replies_list").closest(".comment_container");
        }

        addAnimation(active_comment_item, "animate__fadeOut");

        closeCommentActions();
        setTimeout(() => {
            active_comment_item.remove();

            if(comment_container){
                showRepliesCount(comment_container);
            }
        }, 148);
    }

    return false;
}

async function onSubmitComment(post_form, is_reply = false){
    if(post_form.hasOwnProperty("type")){
        post_form.preventDefault();
        post_form.stopImmediatePropagation();
        post_form = post_form.target;
    }
    let is_mobile_comment = post_form.classList.contains("mobile_add_comment_form");
    let comment_message_field = ux(post_form).find(".comment_message");
    let comment_message = comment_message_field.self().value;
    
    if(comment_message){
        let comment_container = post_form.closest(".comment_container");
        let comment_item = ux("#comments_list_clone .comment_item").clone();
        let comments_list = ux(comment_container).find(".comments_list");
        comment_item.find(".comment_message").text(comment_message);

        if(is_mobile_comment){
            comments_list = ux("#comments_list_container .comments_list");

            if(ux(".active_comment_item").self()){
                comment_container = ux(".active_comment_item").self();
                comments_list = (comment_container.closest(".replies_list")) ? ux(comment_container.closest(".replies_list")) : ux(comment_container).find(".comments_list");
                is_reply = true;
            }
        }
        
        comments_list.self().prepend(comment_item.self());
        addAnimation(comment_item.self(), "animate__zoomIn");

        if(is_reply){
            comment_item.find(".comments_list").self().remove();
            comment_item.find(".add_comment_form").self().remove();
            comment_item.find(".reply_actions .toggle_replies_btn").self().remove();
            
            if(ux(comment_container).find(".toggle_replies_btn").self()){
                ux(comment_container).find(".toggle_replies_btn").self().click();
            }
            
            showRepliesCount(comment_container);
            ux(post_form).find("label").text("Write a reply");
        }

        /** Scroll the mobile comments tab */
        setTimeout(() => {
            ux("#comments_list_container").self().scrollTop = 0;

            if(ux(".active_comment_item").self()){
                ux(".active_comment_item").removeClass("active_comment_item");
                ux(post_form).find("label").text("Write a comment");
                is_mobile_reply_open = false;

                let mobile_list_bounds = comments_list.find(".comment_item").self().getBoundingClientRect();
                ux("#comments_list_container").self().scrollTop = mobile_list_bounds.top - (mobile_list_bounds.height + MOBILE_TOP_OFFSET);
            }
        }, 100);

        post_form.reset();
        comment_message_field.self().blur();
        comment_message_field.self().setAttribute("style","");
    }
    return false;
}

function onEditComment(event){
    event.stopImmediatePropagation();
    let event_target = event.target;

    if(event_target.classList.contains("edit_btn")){
        active_comment_item = (CLIENT_WIDTH > MOBILE_WIDTH) ? event_target.closest(".comment_item") : ux(".active_comment_item").self();
        let comment_content = ux(active_comment_item).find(".comment_content");
        let comment_details = comment_content.find(".comment_details").self();
        let comment_message_value = ux(comment_details).find(".comment_message").text();

        /** Show edit comment form */
        let edit_comment_form = ux("#clone_section_page .edit_comment_form").clone();
        let edit_comment_id = "post_comment_" + new Date().getTime();
        let comment_message_field = edit_comment_form.find(".comment_message");
        let comment_cancel_btn = edit_comment_form.find(".cancel_btn");
        comment_message_field.self().value = comment_message_value;
        comment_message_field.attr("id", edit_comment_id);

        ux(edit_comment_form.self()).on("submit", onSubmitEditForm);
        comment_message_field.on("keydown", onEditMessageKeypress);
        comment_cancel_btn.on("click", closeEditCommentForm);

        if((CLIENT_WIDTH > MOBILE_WIDTH)){
            comment_content.self().before(edit_comment_form.self());
            comment_message_field.self().focus();
        } else {
            is_mobile_reply_open = true;
            comment_content.self().before(edit_comment_form.self());
            comment_message_field.self().focus();
            ux(".mobile_tab_comments").addClass("hidden");
        }

        closeCommentActions();
    }
}

function onSubmitEditForm(event){
    event.stopImmediatePropagation();
    event.preventDefault();
    let edit_comment_form = event.target;
    let comment_message = ux(edit_comment_form).find(".comment_message").self().value;
    let comment_content = edit_comment_form.nextElementSibling;
    ux(comment_content).find(".comment_message").text(comment_message);
    ux(comment_content).find(".posted_at").addClass("edited");
    ux(comment_content).addClass("animate__animated").addClass("animate__pulse");
    closeEditCommentForm(edit_comment_form);
    ux(".active_comment_item").removeClass("active_comment_item");

    setTimeout(() => {
        ux(comment_content).removeClass("animate__animated").removeClass("animate__pulse");    
    }, 480);
    return false;
}

function onEditMessageKeypress(event){
    event.stopImmediatePropagation();
    let edit_comment_form = event.target.closest(".edit_comment_form");
    
    if(event.which === KEYS.ENTER){
        event.preventDefault();
        ux(edit_comment_form).find(".update_btn").self().click();
        return;
    }
    
    if(event.which === KEYS.ESCAPE){
        /** Close edit form */
        closeEditCommentForm(event);
    }
}

function onCommentMessageKeypress(event){
    event.stopImmediatePropagation();
    let comment_message = event.target;
    let post_form = comment_message.closest(".add_comment_form");

    if(event.which === KEYS.ENTER){
        event.preventDefault();
        // onSubmitComment(post_form, comment_message.closest(".comments_list"));
        ux(post_form).trigger("submit");
    }
}

function closeEditCommentForm(event){
    let edit_comment_form = ("type" in event) ? event.target.closest(".edit_comment_form") : event;

    /** Close edit form */
    edit_comment_form.remove();
    ux(".mobile_tab_comments").removeClass("hidden");
}

function showRepliesCount(comment_container){
    let comments_list = ux(comment_container).find(".replies_list");

    if(comments_list.self()){
        let reply_count = comments_list.findAll(".comment_item").length;
        let replies_text = reply_count + ` ${(reply_count == 1) ? "reply" : "replies"}`;
        ux(comment_container).find(".reply_count").text(replies_text);
    }
}