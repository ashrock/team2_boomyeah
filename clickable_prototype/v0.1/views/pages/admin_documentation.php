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
    <script src="<?= add_file("assets/js/vendor/jquery-3.6.3.min.js") ?>"></script>
    <script src="<?= add_file("assets/js/vendor/Sortable.min.js") ?>"></script>
</head>

<body>
    <!--- Add #main_navigation --->
    <div id="main_navigation"><?= include_once("../partials/main_navigation.php") ?></div>
    <!--- Add #invite_modal --->
    <div id="invite_modal"><?= include_once("../partials/invite_modal.php") ?></div>
    <div id="wrapper">
        <div class="container">
            <form action="../../processes/manage_documentation.php" id="add_documentation_form" method="POST">
                <div class="group_add_documentation input-field">
                    <input id="input_add_documentation" type="text" class="validate" name="document_title" autofocus>
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
                    $documentations_order = fetch_record("SELECT documentations_order FROM workspaces WHERE id = {$_SESSION["workspace_id"]};");
                    $documentations_order = $documentations_order["documentations_order"];

                    $documentations = fetch_all("SELECT id, title, is_archived, is_private, cache_collaborators_count
                        FROM documentations
                        WHERE workspace_id = {$_SESSION["workspace_id"]} AND is_archived = {$_NO}
                        ORDER BY FIELD (id, {$documentations_order});
                    ");

                    for($documentations_index = 0; $documentations_index < count($documentations); $documentations_index++){
                        load_view("../partials/document_block_partial.php", $documentations[$documentations_index]);
                    }
                ?>
                <div class="no_documents hidden">
                    <img src="https://village88.s3.us-east-1.amazonaws.com/boomyeah_v2/empty_illustration.png"
                        alt="Empty Content Illustration">
                    <p>You have no documentations yet</p>
                </div>
            </div>
            <div id="archived_documents" class="hidden">
                <!-- Print HTML returned by BE -->
                <div class="no_archived_documents hidden">
                    <img src="https://village88.s3.us-east-1.amazonaws.com/boomyeah_v2/empty_illustration.png"
                        alt="Empty Content Illustration">
                    <p>You have no archived documentations yet</p>
                </div>
            </div>
        </div>
    </div>
    <form id="get_documentations_form" action="../../processes/manage_documentation.php" method="POST">
        <input type="hidden" name="action" value="get_documentations">
        <input type="hidden" id="is_archived" name="is_archived">
    </form>
    <?php include_once("../partials/confirm_documentation_modals.php"); ?>
    <!--JavaScript at end of body for optimized loading-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="<?= add_file("assets/js/main_navigation.js") ?>"></script>
    <script src="<?= add_file("assets/js/admin_documentation.js") ?>"></script>
    <script src="<?= add_file("assets/js/invite_modal.js") ?>"></script>
    <script src="<?= add_file("assets/js/hotkeys.js") ?>"></script>
</body>

</html>