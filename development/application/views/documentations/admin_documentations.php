<?php
    include_once("application/views/view_helper.php");
    #include_once("../../config/connection.php");
    #include_once("../../config/constants.php");
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
    <script src="<?= add_file("assets/js/vendor/ux.lib.js") ?>"></script>
    <script src="<?= add_file("assets/js/vendor/Sortable.min.js") ?>"></script>
</head>

<body>
    <!--- Add #main_navigation --->
    <div id="main_navigation"><?php $this->load->view("partials/main_navigation.php"); ?></div>
    <!--- Add #invite_modal --->
    <div id="invite_modal"><?php # include_once("../partials/invite_modal.php"); ?></div>
    <div id="wrapper">
        <div class="container">
            <form action="/docs/add" id="add_documentation_form" method="POST" autocomplete="off">
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
                    <li><a href="#!" class="switch_view_btn" data-is_archived="0">Documentations</a></li>
                    <li class="divider" tabindex="-1"></li>
                    <li><a href="#!" class="switch_view_btn" data-is_archived="1">Archived</a></li>
                </ul>
            </div>
            <div id="documentations">
                <?php
                    if(count($all_documentations)){
                        for($documentations_index = 0; $documentations_index < count($all_documentations); $documentations_index++){
                            $this->load->view("partials/document_block_partial.php", $all_documentations[$documentations_index]);
                        }
                    }
                    else {
                        $this->load->view("partials/no_documentations_partial.php", array("message" => "You have no documentations yet."));
                    }
                ?>
            </div>
            <div id="archived_documents" class="hidden"></div>
        </div>
    </div>
    <form id="get_documentations_form" action="/docs/get" method="POST">
        <input type="hidden" id="is_archived" name="is_archived">
    </form>
    <form id="duplicate_documentation_form" action="/docs/duplicate" method="POST">
        <input type="hidden" class="documentation_id" name="documentation_id">
    </form>
    <form id="remove_documentation_form" action="/docs/remove" method="POST">
        <input type="hidden" id="remove_documentation_id" name="remove_documentation_id">
        <input type="hidden" id="remove_is_archived" name="remove_is_archived">
    </form>
    <form id="reorder_documentations_form" action="processes/manage_documentation.php" method="POST">
        <input type="hidden" name="action" value="reorder_documentations">
        <input type="hidden" id="documentations_order" name="documentations_order">
    </form>
    <?php $this->load->view("partials/confirm_documentation_modals.php"); ?>
    <!--JavaScript at end of body for optimized loading-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="<?= add_file("assets/js/main_navigation.js") ?>"></script>
    <script src="<?= add_file("assets/js/admin_documentation.js") ?>"></script>
    <script src="<?= add_file("assets/js/invite_modal.js") ?>"></script>
    <script src="<?= add_file("assets/js/hotkeys.js") ?>"></script>
</body>

</html>