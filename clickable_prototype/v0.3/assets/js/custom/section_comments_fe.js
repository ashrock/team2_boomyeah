(function(){
    const current_location = window.location.pathname;
    const view_path = current_location.substring(0, current_location.lastIndexOf('/'));
    let relative_view_paths = view_path.split("views");
    const relative_view_path = relative_view_paths[0] + "views";
    let global_path = (view_path === "/views")? "." : "..";
    let swipe_value = 0;
    let is_comments_displayed = false;
    let swipe_timeout = null;
    let is_mobile_reply_open = false;
    let active_comment_item = null;
    let has_scrolled = false;

    const bindViewEvents = () => {
        ux(".comment_message").onEach("keydown", onCommentMessageKeypress);
        ux(".show_comments_btn").onEach("click", showTabComments);
        ux(".toggle_reply_form_btn").onEach("click", showReplyForm);
        ux(".toggle_replies_btn").onEach("click", showRepliesList);
        ux(".mobile_comment_btn").onEach("click", (event) => {
            event.stopImmediatePropagation();
            onSubmitComment(event.target.closest(".mobile_add_comment_form"))
        });
    }

    document.addEventListener("DOMContentLoaded", async () => {
        await include("#user_view_comments" , `${relative_view_path}/global/user_view_section_comments.html`);

        ux("#section_pages").findAll("ul.comments_list").forEach((comments_list) => {
            if(!comments_list.classList.contains("replies_list")){
                ux(comments_list).findAll(".comment_container").forEach((comment_container) => {
                    showRepliesCount(comment_container);
                });
            }
        });
        ux(document).on("click", onElementClick);

        ux(document).on("scroll", (event) => {
            if(!ux("#prev_page_btn").html().classList.contains("onload") && !has_scrolled){
                if(window.scrollY >= document.body.scrollHeight - (window.innerHeight + (window.innerHeight/4))){
                    ux("#prev_page_btn").addClass("onload");
                    has_scrolled = true;
                }
            }
        })
        
        setTimeout(() => {
            ux("#remove_comment_form").on("submit", onConfirmDeleteComment);
        }, 480);

        /** Mobile Device events */
        ux(document).on("touchstart", function (event){
            swipe_value = event.touches.item(0).clientX;
        });
        ux(document).on("touchend", function (event){
            swipe_value = 0;
            animateSwipe();
        });
        
        ux(document).on("touchmove", function (event){
            clearTimeout(swipe_timeout);
            let event_swipe_value = (event.touches.item(0).clientX);
            let mobile_comments_slideout = ux("#mobile_comments_slideout");
            let swipe_direction = (swipe_value > (event_swipe_value)) ? "left" : "right";

            if(is_comments_displayed){
                if(swipe_value > (event_swipe_value + SWIPE_OFFSET)){
                    if(mobile_comments_slideout.html().classList.contains("active")){
                        mobile_comments_slideout.removeClass("active");
                        let mobile_comment_message = mobile_comments_slideout.find(".mobile_add_comment_form .comment_message");
                        mobile_comment_message.html().value = "";
                        mobile_comment_message.html().blur();
                        
                        /** Wait for comments sidenav to completely hide */
                        setTimeout(() => {
                            is_comments_displayed = false;
                        }, 480);
                    }
                } else {
                    if(!mobile_comments_slideout.html().classList.contains("active")){
                        mobile_comments_slideout.addClass("active");
                    }
                }
            } else {
                let swipe_amount = swipe_value - event_swipe_value;
                
                /** Check swipe only on section pages */
                if(event.target.closest("#section_pages") || event.target.closest("#mobile_section_pages_controls")){
                    if(Math.abs(swipe_amount) > (SWIPE_OFFSET / 2)){
                        animateSwipe(swipe_direction);
                    }
                    
                    if(Math.abs(swipe_amount) > SWIPE_OFFSET){
                        swipe_timeout = setTimeout(() => {
                            onSwipe(swipe_direction);
                        }, 148);
                    }
                }

            }

        });

        bindViewEvents();
    });
    
    async function animateSwipe(swipe_direction = ""){
        let active_section_page = ux("#section_pages .section_page_content.active");
        await active_section_page.removeClass("right");
        await active_section_page.removeClass("left");
        
        if(swipe_direction){
            active_section_page.addClass(swipe_direction);

            if(swipe_direction == "left"){
                ux("#prev_page_btn").removeClass("onload");
            }
        }
    }

    function onSwipe(swipe_direction){
        if(!is_comments_displayed){
            /** Move to prev/next section tab */
            if(swipe_direction == "right" && !ux("#prev_page_btn").html().classList.contains("hidden")){
                ux("#prev_page_btn").html().click();
            }

            if(swipe_direction == "left" && !ux("#next_page_btn").html().classList.contains("hidden")){
                ux("#next_page_btn").html().click();
            }
        }
    }
    
    function onElementClick(event){
        let event_target = event.target;
        let avoid_classes = ["comment_actions_toggle", "edit_btn", "remove_btn"];

        if( avoid_classes.some(avoid_class => event_target.classList.contains(avoid_class)) ){
            event.preventDefault();
            event.stopImmediatePropagation();
            toggleCommentActions(event);
            onEditComment(event);

            showConfirmaDeleteComment(event);
        } else {
            closeCommentActions();
        }
    }

    function showRepliesList(event){
        event.stopImmediatePropagation();
        let show_replies_btn = event.target.closest(".toggle_replies_btn");
        let comment_item = event.target.closest(".comment_item");
        let replies_list  = ux(comment_item).find(".replies_list");
        
        if(!replies_list.html().classList.contains("show")){
            addAnimation(replies_list.html(), "animate__zoomIn");

            replies_list.addClass("show");
            ux(show_replies_btn).addClass("hidden");
        }
    }

    async function showReplyForm(event){
        event.stopImmediatePropagation();
        let comment_item = event.target.closest(".comment_item");
        let label_text = "Replying to " + ux(comment_item).find(".user_name").text();

        if(ux(".active_comment_item").html()){
            await ux(".active_comment_item").removeClass("active_comment_item");
        }
    
        if((CLIENT_WIDTH > MOBILE_WIDTH)){
            let reply_form = ux(comment_item).find(".add_reply_form");
            
            if(!reply_form.html()){
                reply_form = ux(comment_item.closest(".replies_list").closest(".comment_item").querySelector(".add_reply_form"));
                reply_form.find("label").text(label_text);
            }
            
            if(!reply_form.html().classList.contains("show")){
                reply_form.addClass("show");
                addAnimation(reply_form.html(), "animate__zoomIn");
            }
    
            reply_form.find(".comment_message").html().focus();
        } else {
            is_mobile_reply_open = true;

            ux(comment_item).addClass("active_comment_item");
            let mobile_add_comment_form = ux(".mobile_add_comment_form");
            mobile_add_comment_form.find("label").text(label_text);
            mobile_add_comment_form.find("textarea").html().focus();
        }
    }

    function closeCommentActions(){
        ux(document).findAll(".comment_actions_toggle").forEach((element) => ux(element).removeClass("active"));
        ux("#comment_actions_container").removeClass("active");
        (!is_mobile_reply_open && ux(".active_comment_item").html()) && ux(".active_comment_item").removeClass("active_comment_item");
    }

    async function onSubmitComment(post_form, is_reply = false){
        if(post_form.hasOwnProperty("type")){
            post_form.preventDefault();
            post_form.stopImmediatePropagation();
            post_form = post_form.target;
        }
        let is_mobile_comment = post_form.classList.contains("mobile_add_comment_form");
        let comment_message_field = ux(post_form).find(".comment_message");
        let comment_message = comment_message_field.html().value;
        
        if(comment_message){
            let comment_container = post_form.closest(".comment_container");
            let comment_item = ux("#comments_list_clone .comment_item").clone();
            let comments_list = ux(comment_container).find(".comments_list");
            comment_item.find(".comment_message").text(comment_message);

            if(is_mobile_comment){
                comments_list = ux("#comments_list_container .comments_list");

                if(ux(".active_comment_item").html()){
                    comment_container = ux(".active_comment_item").html();
                    comments_list = (comment_container.closest(".replies_list")) ? ux(comment_container.closest(".replies_list")) : ux(comment_container).find(".comments_list");
                    is_reply = true;
                }
            }
            
            comments_list.html().prepend(comment_item.html());
            addAnimation(comment_item.html(), "animate__zoomIn");

            if(is_reply){
                comment_item.find(".comments_list").html().remove();
                comment_item.find(".add_comment_form").html().remove();
                comment_item.find(".reply_actions .toggle_replies_btn").html().remove();
                
                if(ux(comment_container).find(".toggle_replies_btn").html()){
                    ux(comment_container).find(".toggle_replies_btn").html().click();
                }
                
                showRepliesCount(comment_container);
                ux(post_form).find("label").text("Write a reply");
            }

            /** Scroll the mobile comments tab */
            setTimeout(() => {
                ux("#comments_list_container").html().scrollTop = 0;

                if(ux(".active_comment_item").html()){
                    ux(".active_comment_item").removeClass("active_comment_item");
                    ux(post_form).find("label").text("Write a comment");
                    is_mobile_reply_open = false;

                    let mobile_list_bounds = comments_list.find(".comment_item").html().getBoundingClientRect();
                    ux("#comments_list_container").html().scrollTop = mobile_list_bounds.top - (mobile_list_bounds.height + MOBILE_TOP_OFFSET);
                }
            }, 100);

            post_form.reset();
            comment_message_field.html().blur();
            comment_message_field.html().setAttribute("style","");
            bindViewEvents();
        }
        return false;
    }

    function showRepliesCount(comment_container){
        let comments_list = ux(comment_container).find(".replies_list");

        if(comments_list.html()){
            let reply_count = comments_list.findAll(".comment_item").length;
            let replies_text = reply_count + ` ${(reply_count == 1) ? "reply" : "replies"}`;
            ux(comment_container).find(".reply_count").text(replies_text);
        }
    }

    async function showTabComments(event){
        event.preventDefault();
        let mobile_comments_slideout = ux("#mobile_comments_slideout");
        mobile_comments_slideout.find("#user_comments_list").html().innerHtml = "";

        if(!mobile_comments_slideout.html().classList.contains("active")){
            await include("#user_comments_list" , `${relative_view_path}/global/user_view_section_comments.html`);
            mobile_comments_slideout.addClass("active");
            is_comments_displayed = true;

            ux("#mobile_comments_slideout").findAll("ul.comments_list").forEach((comments_list) => {
                if(!comments_list.classList.contains("replies_list")){
                    ux(comments_list).findAll(".comment_container").forEach((comment_container) => {
                        showRepliesCount(comment_container);
                    });
                }
            });

            bindViewEvents();
        }
    }

    function onEditComment(event){
        event.stopImmediatePropagation();
        let event_target = event.target;

        if(event_target.classList.contains("edit_btn")){
            active_comment_item = (CLIENT_WIDTH > MOBILE_WIDTH) ? event_target.closest(".comment_item") : ux(".active_comment_item").html();
            let comment_content = ux(active_comment_item).find(".comment_content");
            let comment_details = comment_content.find(".comment_details").html();
            let comment_message_value = ux(comment_details).find(".comment_message").text();

            /** Show edit comment form */
            let edit_comment_form = ux("#clone_section_page .edit_comment_form").clone();
            let edit_comment_id = "post_comment_" + new Date().getTime();
            let comment_message_field = edit_comment_form.find(".comment_message");
            let comment_cancel_btn = edit_comment_form.find(".cancel_btn");
            comment_message_field.html().value = comment_message_value;
            comment_message_field.attr("id", edit_comment_id);

            ux(edit_comment_form.html()).on("submit", onSubmitEditForm);
            comment_message_field.on("keydown", onEditMessageKeypress);
            comment_cancel_btn.on("click", closeEditCommentForm);

            if((CLIENT_WIDTH > MOBILE_WIDTH)){
                comment_content.html().before(edit_comment_form.html());
                comment_message_field.html().focus();
            } else {
                is_mobile_reply_open = true;
                comment_content.html().before(edit_comment_form.html());
                comment_message_field.html().focus();
                ux(".mobile_tab_comments").addClass("hidden");
            }

            closeCommentActions();
        }
    }

    function showConfirmaDeleteComment(event){
        event.stopImmediatePropagation();
        let event_target = event.target;

        if(event_target.classList.contains("remove_btn")){
            let remove_comment_modal = ux("#confirm_remove_comment_modal");
            let modal_instance = M.Modal.getInstance(remove_comment_modal);
            modal_instance.open();
            ux("#remove_comment_form").on("submit", onConfirmDeleteComment);

            /** Determine active_comment_item */
            active_comment_item = (CLIENT_WIDTH > MOBILE_WIDTH) ? event_target.closest(".comment_item") : ux(".active_comment_item").html();
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

    function toggleCommentActions(event){
        let event_target = event.target;

        if(event_target.classList.contains("comment_actions_toggle")){
            let viewport_width = document.documentElement.clientWidth;

            if(viewport_width > MOBILE_WIDTH){
                if(event_target.classList.contains("active")){
                    event_target.classList.remove("active");
                } else {
                    event_target.classList.add("active");
                }
            } else {
                event.stopImmediatePropagation();

                if(ux(".active_comment_item").html()){
                    ux(".active_comment_item").removeClass("active_comment_item");
                }
                ux("#comment_actions_container").addClass("active");
                ux(event_target.closest(".comment_item")).addClass("active_comment_item");
            }
        }
    }

    function onSubmitEditForm(event){
        event.stopImmediatePropagation();
        event.preventDefault();
        let edit_comment_form = event.target;
        let comment_message = ux(edit_comment_form).find(".comment_message").html().value;
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
            ux(edit_comment_form).find(".update_btn").html().click();
            return;
        }
        
        if(event.which === KEYS.ESCAPE){
            /** Close edit form */
            closeEditCommentForm(event);
        }
    }

    function closeEditCommentForm(event){
        let edit_comment_form = ("type" in event) ? event.target.closest(".edit_comment_form") : event;

        /** Close edit form */
        edit_comment_form.remove();
        ux(".mobile_tab_comments").removeClass("hidden");
    }

    function onCommentMessageKeypress(event){
        event.stopImmediatePropagation();
        let comment_message = event.target;
        let post_form = comment_message.closest(".add_comment_form");

        if(event.which === KEYS.ENTER){
            event.preventDefault();
            onSubmitComment(post_form, comment_message.closest(".comments_list"));
        }
    }
})();