<?php
    session_start();

    // Sample admin session
    $_SESSION["user_id"]       = 1;
    $_SESSION["user_level_id"] = 9;
    $_SESSION["workspace_id"]  = 1;
    // END

    include_once("../../processes/partial_helper.php");  
    include_once("../view_helper.php");   
    include_once("../../config/connection.php");
    include_once("../../config/constants.php");

    /** TODO: Backend should provide the $document_id */
    $document_id = time();
    $document_data = array(
        "document_id" => $document_id,
        "document_title" => "Employee Handbook",
        "is_private" => TRUE
    );

    //load initial data from json file
    $sections_data_file_path = "../../assets/json/sections_data.json";
    $sections_data = load_json_file($sections_data_file_path);
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
    <title>Boom Yeah | User View Documentation Page</title>
    <link rel="shortcut icon" href="<?= add_file("assets/images/favicon.ico") ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= add_file("assets/css/global.css") ?>">
    <link rel="stylesheet" href="<?= add_file("assets/css/user_view_documentation.css") ?>">
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="<?= add_file("assets/js/vendor/html_loader.lib.js") ?>"></script>
    <script src="<?= add_file("assets/js/vendor/ux.lib.js") ?>"></script>
</head>
<body>
    <!--- Add #main_navigation --->
    <div id="main_navigation"></div>
    <!--- Add #invite_modal --->
    <div id="invite_modal"></div>
    
    <div id="wrapper">
        <div class="container">
            <ul id="breadcrumb_list">
                <li class="breadcrumb_item"><a href="admin_documentation.html">Documentation</a></li>
                <li class="breadcrumb_item mobile_breadcrumb"><a href="user_documentation.html">&lt;</a></li>
                <li class="breadcrumb_item active">Employee Handbook</li>
            </ul>
            <div class="divider"></div>
            <div id="doc_title_access">
                <h1 id="doc_title">Employee Handbook</h1>
            </div>
            <p class="doc_text_content">This handbook replaces and supersedes all prior employee handbooks regarding employment or HR matters effective January 01, 2021. The policies and practices included in this handbook may be modified at any time. Your department has additional specific procedures for many of the general policies stated in the handbook. You are expected to learn your department's procedures and comply with them. You are also expected to conform to the professional standards of your occupation. Please direct any questions to your supervisor, department head, or to the Human Resources Management and Development Office.</p>
            <div class="section_header">
                <h2>Sections</h2>
                <button class="sort_btn dropdown-trigger" data-target="sort_list">Sort by</button>
                <!-- Dropdown Structure -->
                <ul id="sort_list" class="dropdown-content sort_dropdown">
                    <li><a href="#!" class="sort_by" data-sort-by="az">A-Z</a></li>
                    <li><a href="#!" class="sort_by" data-sort-by="za">Z-A</a></li>
                </ul>
            </div>
            <div class="section_container" id="section_container">
                <?php 
                    if(count($sections_data["fetch_section_user_data"])){
                        foreach($sections_data["fetch_section_user_data"] as $section_data){
                            load_view("../partials/section_block_partial.php", $section_data);
                        }
                    }
                    else{
                        //display if no sections
                    }
                ?>
            </div>
        </div>
    </div>
    <!--JavaScript at end of body for optimized loading-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="<?= add_file("assets/js/hotkeys.js") ?>"></script>
    <script src="<?= add_file("assets/js/user_view_documentation.js") ?>"></script>
</body>
</html> 