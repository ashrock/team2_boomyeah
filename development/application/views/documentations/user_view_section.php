<?php
    include_once("application/views/view_helper.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Company - Employee Handbook | BoomYEAH</title>
    <link rel="shortcut icon" href="<?= add_file("assets/images/favicon.ico") ?>" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <link rel="stylesheet" href="<?= add_file("assets/css/vendor/redactorx.min.css") ?>">
    <link rel="stylesheet" href="<?= add_file("assets/css/global.css") ?>">
    <link rel="stylesheet" href="<?= add_file("assets/css/user_view_section.css") ?>">
    <script src="<?= add_file("assets/js/vendor/Sortable.min.js") ?>"></script>
    <script src="<?= add_file("assets/js/vendor/ux.lib.js") ?>"></script>
    <script src="<?= add_file("assets/js/constants.js") ?>"></script>
</head>
<body>
    <div id="main_navigation"><?php $this->load->view("partials/main_navigation.php", array("view_page" => "Sections")); ?></div>
    <div id="wrapper" class="container">
        <div id="view_section_content">
            <div id="section_summary">
                <div class="breadcrumbs">
                    <ul id="breadcrumbs_list">
                        <li class="breadcrumb_item"><a href="/docs">Documentations</a></li class="breadcrumb_item">
                        <li class="breadcrumb_item"><a href="/docs/<?= $documentation['id'] ?>"><?= $documentation["title"] ?></a></li class="breadcrumb_item">
                        <li class="breadcrumb_item active"><span><?= $section["title"] ?></span></li>
                    </ul>
                    <div class="row_placeholder"></div>
<?php if($_SESSION["user_level_id"] == USER_LEVEL["ADMIN"]){ ?> 
                    <a href="/docs/<?= "{$documentation['id']}/{$section["id"]}/edit" ?>" id="preview_section_btn">Back to Edit</a>
<?php } ?>
                </div>
                <div class="section_details">
                    <div id="section_title_content">
                        <a class="mobile_document_link" href="/docs/<?= $documentation['id'] ?>"></a>
                        <h1 id="section_title"><?= $section["title"] ?></h1>
                    </div>
                    <div class="add_description">
                        <p name="section_short_description" id="section_short_description"><?= $section["description"] ?></p>
                    </div>
                </div>
            </div>
            <div id="section_pages">
                <?php
                    $this->load->view("partials/user_section_page_content_partial.php", array("modules" => $modules));
                ?>
                </div>
                <!-- Mobile View: Progress bar corresponds to the section_page_content not the section_page_tab  -->
            </div>
            <div id="mobile_section_pages_controls">
                <div class="row_placeholder"></div>
                <div id="page_btns">
                    <button id="prev_page_btn" type="button" class="page_btn hidden"></button>
                    <button id="next_page_btn" type="button" class="page_btn"></button>
                </div>
            </div>
            <div id="clone_section_page">
                <?php $this->load->view("partials/clone_section_page.php"); ?>
            </div>
        </div>
    </div>
    <div id="comment_actions_container">
        <div class="comment_actions_menu">
            <button type="button" class="comment_action_btn edit_btn">Edit</button>
            <button type="button" class="comment_action_btn remove_btn">Remove</button>
        </div>
    </div>
    <div id="mobile_comments_slideout" class="sidenav">
        <div id="comments_list_container">
            <ul id="user_comments_list" class="comments_list"></ul>
        </div>
        <div class="mobile_tab_comments tab_comments comment_container">
            <form action="/posts/add" method="POST" class="mobile_add_comment_form add_comment_form show">
                <div class="comment_field">
                    <input type="hidden" name="action" value="add_tab_post" class="action">
                    <input type="hidden" name="tab_id" class="tab_id" value="">
                    <div class="comment_message_content input-field col s12">
                        <label for="mobile_comment_message">Write a comment</label>
                        <textarea name="post_comment" id="mobile_comment_message" class="materialize-textarea comment_message"></textarea>
                    </div>
                </div>
                <div class="comment_btn">
                    <button type="submit" class="mobile_comment_btn"></button>
                </div>
            </form>
            <form action="/posts/add_comment" method="POST" class="mobile_add_reply_form add_comment_form">
                <div class="comment_field">
                    <input type="hidden" name="action" value="add_post_comment" class="action">
                    <input type="hidden" name="post_id" class="post_id" value="">
                    <div class="comment_message_content input-field col s12">
                        <label for="mobile_comment_message">Write a comment</label>
                        <textarea name="post_comment" id="mobile_comment_message" class="materialize-textarea comment_message"></textarea>
                    </div>
                </div>
                <div class="comment_btn">
                    <button type="submit" class="mobile_comment_btn"></button>
                </div>
            </form>
        </div>
    </div>
    <div id="modals_container">
        <?php $this->load->view("partials/confirm_action_modals.php"); ?>
    </div>
    <form id="fetch_tab_posts_form" action="/posts/get" method="POST" class="hidden">
        <input type="hidden" name="action" value="fetch_tab_posts">
        <input type="hidden" name="tab_id" class="tab_id">
    </form>
    <form id="fetch_mobile_posts_form" action="/posts/get" method="POST" class="hidden">
        <input type="hidden" name="action" value="fetch_tab_posts">
        <input type="hidden" name="tab_id" class="tab_id">
    </form>

    <form id="fetch_post_comments_form" action="/posts/get_comments" method="POST" class="hidden">
        <input type="hidden" name="action" value="fetch_post_comments">
        <input type="hidden" name="post_id" class="post_id">
    </form>
    <script src="<?= add_file("assets/js/vendor/redactorx.min.js") ?>"></script>
    <script src="<?= add_file("assets/js/main_navigation.js") ?>"></script>
    <script src="<?= add_file("assets/js/custom/admin_edit_section/admin_edit_section_fe.js") ?>"></script>
    <script src="<?= add_file("assets/js/custom/admin_edit_section/admin_edit_section_be.js") ?>"></script>
    <script src="<?= add_file("assets/js/custom/module_tab_comments/module_tab_comments_fe.js") ?>"></script>
    <script src="<?= add_file("assets/js/custom/module_tab_comments/module_tab_comments_be.js") ?>"></script>
</body>
</html>