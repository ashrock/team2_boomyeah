<?php
    foreach($comment_items as $comment_item) { ?>
<li class="comment_item comment_container">
    <div class="comment_content">
        <img src="https://village88.s3.us-east-1.amazonaws.com/boomyeah_v2/jhaver.png" alt="Jhaver" class="user_image"/> 
        <div class="comment_details">
            <h4 class="commenter_name">
                <span class="user_name">Ben T. Lador</span><span class="posted_at">• a few seconds ago</span>
                <div class="comment_actions">
                    <button type="button" class="comment_actions_toggle"></button>
                    <div class="comment_actions_menu">
                        <button type="button" class="comment_action_btn edit_btn">Edit</button>
                        <button type="button" class="comment_action_btn remove_btn">Remove</button>
                    </div>
                </div>
            </h4>
            <p class="comment_message">Podcasting operational change management inside of workflows to establish a framework. </p>
        </div>
    </div>
    <div class="reply_actions">
        <button type="button" class="toggle_reply_form_btn" data-target_comment="<?= isset($comment_item["post_id"]) ? $comment_item["post_id"] : $comment_item["comment_id"] ?>">Reply</button>
        <?php if(isset($comment_item["post_id"])) { ?>
        <button type="button" class="toggle_replies_btn"><b>Show <span class="reply_count"> replies</span></b></button>
        <?php } ?>
    </div>
    <?php if(isset($comment_item["post_id"])) { ?>
    <ul class="replies_list comments_list">
    <?php 
        load_view( $views_path ."partials/replies_item_partial.php", array("comment_items" => $comment_item["comments"]));
    ?>
    </ul>
    <?php } ?>
</li>
<?php } ?>