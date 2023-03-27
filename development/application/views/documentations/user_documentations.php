<?php include_once("application/views/view_helper.php"); ?>
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
    <script src="<?= add_file("assets/js/vendor/ux.lib.js") ?>"></script>
</head>
<body>
    <!--- Add #main_navigation --->
    <div id="main_navigation"><?php $this->load->view("partials/main_navigation.php"); ?></div>
    <div class="user">
        <div class="container" id="user_doc">
            <button id="docs_view_btn" class="dropdown-trigger" data-target="docs_view_list">Documentations</button>
            <div id="documentations">
                <?php
                    if(count($all_documentations)){
                        $this->load->view("partials/document_block_partial.php", $all_documentations);
                    }
                    else {
                        $this->load->view("partials/no_documentations_partial.php", array("message" => "There are no documentations yet."));
                    }
                ?>
            </div>
        </div>
    </div>
    <!--JavaScript at end of body for optimized loading-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="<?= add_file("assets/js/main_navigation.js") ?>"></script>
    <script src="<?= add_file("assets/js/custom/user_documentation/user_documentation.js") ?>"></script>
</body>

</html>