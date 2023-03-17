<?php
    session_start();

    // Sample admin session
    $_SESSION["user_id"]       = 3;
    $_SESSION["user_level_id"] = 1;
    $_SESSION["workspace_id"]  = 1;
    // END

    include_once("../../processes/partial_helper.php");  
    include_once("../view_helper.php");  
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
    <title>Boom Yeah | User Documentation Page</title>
    <link rel="shortcut icon" href="<?= add_file("assets/images/favicon.ico") ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= add_file("assets/css/global.css") ?>">
    <link rel="stylesheet" href="<?= add_file("assets/css/user_documentation.css") ?>">
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="<?= add_file("assets/js/vendor/ux.lib.js") ?>"></script>
    <script src="<?= add_file("assets/js/vendor/Sortable.min.js") ?>"></script>
</head>
<body>
    <!--- Add #main_navigation --->
    <div id="main_navigation"><?php include_once("../partials/main_navigation.php"); ?></div>
    <div class="user">
        <div class="container" id="user_doc">
            <button id="docs_view_btn" class="dropdown-trigger" data-target="docs_view_list">Documentations</button>
            <div id="documentations">
                <?php
                    if(count($documentation_data["fetch_user_data"])){
                        foreach($documentation_data["fetch_user_data"] as $fetch_user_data){
                    ?>
                        <div class="document_block mobile_block">
                            <div class="document_details">
                                <h2><?= $fetch_user_data["title"] ?></h2>
                                <?php if($fetch_user_data["is_private"]){ ?>
                                    <button class="invite_collaborators_btn"><?= $fetch_user_data["cache_collaborators_count"] ?></button> 
                                <?php  } ?>
                            </div>
                            <?php if($fetch_user_data["is_private"]){ ?>
                                <div class="document_controls"><button class="access_btn"></button></div>
                            <?php  } ?>
                        </div>
                    <?php
                        }
                    }
                    else {
                        load_view("../partials/no_documentations_partial.php", array("message" => "You have no documentations yet."));
                    }
                ?> 
            </div>
        </div>
    </div>
    <!--JavaScript at end of body for optimized loading-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="<?= add_file("assets/js/main_navigation.js") ?>"></script>
    <script src="<?= add_file("assets/js/hotkeys.js") ?>"></script>
    <script src="<?= add_file("assets/js/custom/user_documentation/user_documentation_fe.js") ?>"></script>
</body>

</html>