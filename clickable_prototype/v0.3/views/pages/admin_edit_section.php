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
    $document_title = (isset($_GET["document_title"])) ? htmlspecialchars_decode( $_GET["document_title"] ) : "Employee Handbook";
    $section_title = (isset($_GET["section_title"])) ? htmlspecialchars_decode( $_GET["section_title"] ) : "About Company";

    $edit_section_module_file_path = "../../assets/json/edit_section_module_data.json";
    $edit_section_module_data = load_json_file($edit_section_module_file_path);
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
    <link rel="stylesheet" href="<?= add_file("assets/css/admin_edit_section.css") ?>">
    <script src="<?= add_file("assets/js/vendor/Sortable.min.js") ?>"></script>
    <script src="<?= add_file("assets/js/vendor/ux.lib.js") ?>"></script>
    <script src="<?= add_file("assets/js/constants.js") ?>"></script>
</head>
<body>
    <div id="main_navigation"><?php include_once("../partials/main_navigation.php"); ?></div>
    <div id="wrapper" class="container">
        <div id="edit_section_content">
            <form action="<?= BASE_FILE_URL ?>processes/manage_documentation.php" method="POST" id="edit_section_form">
                <input type="hidden" name="action" value="update_section">
                <input type="hidden" name="section_id" value="section_id" value="">
                <div id="section_summary">
                    <div class="breadcrumbs">
                        <ul id="breadcrumbs_list">
                            <li class="breadcrumb_item"><a href="admin_documentation.php">Documentations</a></li class="breadcrumb_item">
                            <li class="breadcrumb_item"><a href="admin_edit_documentation.php">Employee Handbook</a></li class="breadcrumb_item">
                            <li class="breadcrumb_item active"><span>About Company</span></li>
                        </ul>
                        <div class="row_placeholder"></div>
                        <a href="user_view_section.php/?view_type=<?= $section_title ?>" id="preview_section_btn">Preview</a>
                    </div>
                    <div class="section_details">
                        <h1 id="section_title">About Company</h1>

                        <div class="add_description">
                            <textarea name="section_short_description" id="section_short_description" placeholder="Add Description">Village 88 Inc. is a US-Delaware corporation which focuses on incubating companies and providing IT consultancy services to companies in the US. V88 also has a remote branch in San Fernando, La Union, Philippines registered in Securities and Exchange Commission as 457Avenue Inc.

Village 88 Inc. was founded in 2011 while 457Avenue Inc. registered in the Philippines in 2013. It is the company’s vision to provide world-class IT education to brilliant individuals with less IT-career opportunity due to lack of industry experience or exposure. So far, Village 88, Inc. (V88) has produced 30+ talented software engineers from the Philippines who now worked with the company in incubating and launching businesses that bring a positive impact to the world. </textarea>
                        </div>
                    </div>
                </div>
            </form>
                <div id="section_pages">
                    <?php
                        foreach($edit_section_module_data["fetch_admin_module_data"] as $module_data){
                            $modules_array = array("modules" => array($module_data));
                            if($modules_array){
                                load_view("../partials/section_page_content_partial.php", $modules_array);
                            }
                            else{
                                //when no data display nothing
                            }
                        }
                    ?>
                </div>
            <form id="add_module_form" action="<?= BASE_FILE_URL ?>processes/manage_documentation.php" method="POST">
                <input type="hidden" name="action" value="add_module">
                <button id="add_page_tabs_btn" type="submit">+ Add New</button>
            </form>
            <form id="add_module_tab_form" action="<?= BASE_FILE_URL ?>processes/manage_documentation.php" method="POST" class="hidden">
                <input type="hidden" name="action" value="add_module_tab">
                <input type="hidden" name="module_id" class="module_id">
            </form>
            <form id="reorder_tabs_form" action="<?= BASE_FILE_URL ?>processes/manage_documentation.php" method="POST" class="hidden">
                <input type="hidden" name="action" value="reorder_tabs">
                <input type="hidden" name="module_id" class="module_id">
                <input type="hidden" name="tab_ids_order" class="tab_ids_order">
            </form>
            <div id="clone_section_page">
                <?php include_once("../partials/clone_section_page.php"); ?>
            </div>
            <div id="modals_container">
                <?php include_once("../partials/confirm_action_modals.php"); ?>
            </div>
        </div>
    </div>
    <script src="<?= add_file("assets/js/vendor/redactorx.min.js") ?>"></script>
    <script src="<?= add_file("assets/js/custom/admin_edit_section/admin_edit_section_fe.js") ?>"></script>
    <script src="<?= add_file("assets/js/custom/admin_edit_section/admin_edit_section_be.js") ?>"></script>
</body>
</html>