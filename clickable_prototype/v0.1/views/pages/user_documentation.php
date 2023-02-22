<?php
    session_start();

    // Sample admin session
    $_SESSION["user_id"]       = 3;
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
    <title>Boom Yeah | User Documentation Page</title>
    <link rel="shortcut icon" href="<?= add_file("assets/images/favicon.ico") ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= add_file("assets/css/global.css") ?>">
    <link rel="stylesheet" href="<?= add_file("assets/css/user_documentation.css") ?>">
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="<?= add_file("assets/js/vendor/jquery-3.6.3.min.js") ?>"></script>
    <script src="<?= add_file("assets/js/vendor/Sortable.min.js") ?>"></script>
</head>
<body>
    <!--- Add #main_navigation --->
    <div id="main_navigation"><?= include_once("../partials/main_navigation.php") ?></div>
    <div class="user">
        <div class="container" id="user_doc">
            <button id="docs_view_btn" class="dropdown-trigger" data-target="docs_view_list">Documentations</button>
            <div id="documentations">
                <?php
                    $documentations_order = fetch_record("SELECT documentations_order FROM workspaces WHERE id = {$_SESSION["workspace_id"]};");
                    $documentations_order = $documentations_order["documentations_order"];

                    $documentations = fetch_all("SELECT id, title, is_private, cache_collaborators_count
                        FROM documentations
                        WHERE workspace_id = {$_SESSION["workspace_id"]} AND (is_private = 0 OR id IN (SELECT documentation_id FROM collaborators WHERE user_id = {$_SESSION["user_id"]} )) AND is_archived = {$_NO}
                        ORDER BY FIELD (id, {$documentations_order});
                    ");

                    for($documentations_index = 0; $documentations_index < count($documentations); $documentations_index++){ ?>
                        <div class="document_block">
                            <div class="document_details">
                                <h2><?= $documentations[$documentations_index]["title"] ?></h2>
                                <?php if($documentations[$documentations_index]["is_private"]){ ?>
                                    <button class="invite_collaborators_btn"><?= $documentations[$documentations_index]["cache_collaborators_count"] ?></button> 
                                <?php  } ?>
                            </div>
                            <?php if($documentations[$documentations_index]["is_private"]){ ?>
                                <div class="document_controls"><button class="access_btn"></button></div>
                            <?php  } ?>
                        </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!--JavaScript at end of body for optimized loading-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="<?= add_file("assets/js/main_navigation.js") ?>"></script>
    <script src="<?= add_file("assets/js/hotkeys.js") ?>"></script>
    <script src="<?= add_file("assets/js/user_documentation.js") ?>"></script>
</body>
</html>