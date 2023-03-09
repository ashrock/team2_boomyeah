<?php
    session_start();

    // Sample admin session
    $_SESSION["user_id"]       = 1;
    $_SESSION["user_level_id"] = 9;
    $_SESSION["workspace_id"]  = 1;
    // END

    include_once("../view_helper.php");  
    include_once("../../config/connection.php");
    include_once("../../config/constants.php");

    // Load initial data
    $documentation_data_file = "../../assets/json/documentation_data.json";
    $documentation_data = load_json_file($documentation_data_file);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="UX Team 2">
    <meta name="description" content="A great way to describe your documentation tool">
    <title>Boom Yeah | Admin Documentation Page</title>
    <link rel="shortcut icon" href="<?= add_file("assets/images/favicon.ico") ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= add_file("assets/css/global.css") ?>">
    <link rel="stylesheet" href="<?= add_file("assets/css/admin_documentation.css") ?>">
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="<?= add_file("assets/js/vendor/Sortable.min.js") ?>"></script>
    <script src="<?= add_file("assets/js/vendor/ux.lib.js") ?>"></script>
</head>

<body>
    <!--- Add #main_navigation --->
    <div id="main_navigation"><?php include_once("../partials/main_navigation.php"); ?></div>
    <!--- Add #invite_modal --->
    <div id="invite_modal"><?php include_once("../partials/invite_modal.php"); ?></div>
    <div id="wrapper">
        <div class="container">
            <form action="<?= BASE_FILE_URL ?>processes/manage_documentation.php" id="add_documentation_form" method="POST" autocomplete="off">
                <div class="group_add_documentation input-field">
                    <input id="input_add_documentation" type="text" class="validate" name="document_title" autofocus autocomplete="nope">
                    <input type="hidden" name="action" value="create_documentation">
                    <label for="input_add_documentation">Add Documentation</label>
                </div>
                <span id="save_status" hidden>Saving...</span>
            </form>
            <div class="section_header">
                <button id="docs_view_btn" class="dropdown-trigger" data-target="docs_view_list">Documentations</button>
                <ul id='docs_view_list' class='dropdown-content'>
                    <li><a href="#!" class="active_docs_btn">Documentations</a></li>
                    <li class="divider" tabindex="-1"></li>
                    <li><a href="#!" class="archived_docs_btn">Archived</a></li>
                </ul>
            </div>
            <div id="documentations">
                <?php
                   $filtered_documentations = [];
                   $filtered_documentations = array_filter($documentation_data["fetch_admin_data"], function($data) {
                       return $data["is_archived"] == 0;
                   });
                   if(count($filtered_documentations)){
                       foreach ($filtered_documentations as $fetch_admin_data) {
                           load_view("../partials/document_block_partial.php", $fetch_admin_data);
                       }
                   }
                   else {
                       load_view("../partials/no_documentations_partial.php", array("message" => "You have no documentations yet."));
                   }
                ?>
            </div>
            <div id="archived_documents" class="hidden"></div>
        </div>
    </div>
    <form id="get_documentations_form" action="<?= BASE_FILE_URL ?>processes/manage_documentation.php" method="POST">
        <input type="hidden" name="action" value="get_documentations">
        <input type="hidden" id="is_archived" name="is_archived">
    </form>
    <form action="<?= BASE_FILE_URL ?>processes/manage_documentation.php" id="duplicate_documentation_form" method="POST">
        <input type="hidden" name="action" value="duplicate_documentation">
        <input type="hidden" class="documentation_id" name="documentation_id">
    </form>
    <form id="remove_documentation_form" action="<?= BASE_FILE_URL ?>processes/manage_documentation.php" method="POST">
        <input type="hidden" name="action" value="remove_documentation">
        <input type="hidden" id="remove_documentation_id" name="remove_documentation_id">
        <input type="hidden" id="remove_is_archived" name="remove_is_archived">
    </form>
    <form id="reorder_documentations_form" action="<?= BASE_FILE_URL ?>processes/manage_documentation.php" method="POST">
        <input type="hidden" name="action" value="reorder_documentations">
        <input type="hidden" id="documentations_order" name="documentations_order">
    </form>
    <?php include_once("../partials/confirm_documentation_modals.php"); ?>
    <?php include_once("../partials/confirm_invite_modals.php"); ?>
    <!--JavaScript at end of body for optimized loading-->
    <script src="<?= add_file("assets/js/main_navigation.js") ?>"></script>
    <script src="<?= add_file("assets/js/invite_modal.js") ?>"></script>
    <script src="<?= add_file("assets/js/custom/admin_documentation/admin_documentation_fe.js") ?>"></script>
    <script src="<?= add_file("assets/js/custom/admin_documentation/admin_documentation_be.js") ?>"></script>
    <script src="<?= add_file("assets/js/hotkeys.js") ?>"></script>
</body>

</html>