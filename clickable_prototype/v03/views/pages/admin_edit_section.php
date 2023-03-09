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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Company - Employee Handbook | BoomYEAH</title>
    <link rel="shortcut icon" href="../../../assets/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

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
            <form action="#" method="POST" id="edit_section_form">
                <div id="section_summary">
                    <div class="breadcrumbs">
                        <ul id="breadcrumbs_list">
                            <li class="breadcrumb_item"><a href="admin_documentation.html">Documentations</a></li class="breadcrumb_item">
                            <li class="breadcrumb_item"><a href="admin_edit_documentation.html">Employee Handbook</a></li class="breadcrumb_item">
                            <li class="breadcrumb_item active"><span>About Company</span></li>
                        </ul>
                        <div class="row_placeholder"></div>
                        <a href="../default_data/preview_section.html" id="preview_section_btn">Preview</a>
                    </div>
                    <div class="section_details">
                        <h1 id="section_title">About Company</h1>

                        <div class="add_description">
                            <textarea name="section_short_description" id="section_short_description" placeholder="Add Description">Village 88 Inc. is a US-Delaware corporation which focuses on incubating companies and providing IT consultancy services to companies in the US. V88 also has a remote branch in San Fernando, La Union, Philippines registered in Securities and Exchange Commission as 457Avenue Inc.</textarea>
                        </div>
                    </div>
                </div>
                <div id="section_pages">
                    <?php
                        $module_id = time() + rand();
                        $tab_id = time() + rand();
                        $module_data = array(
                            "id" => $module_id,
                            "module_tabs_json" => array(
                                array(
                                    "id" => $tab_id,
                                    "title" => "Tab ". $tab_id ." Module ". $module_id,
                                    "content" => "Sample",
                                    "module_id" => $module_id,
                                    "is_comments_allowed" => 0
                                )
                            )
                        );
                        $modules_array = array("modules" => array($module_data));
                        load_view("../partials/section_page_content_partial.php", $modules_array);
                    ?>
                </div>
            </form>
            <form id="add_module_form" action="<?= BASE_FILE_URL ?>processes/manage_documentation.php" method="POST">
                <input type="hidden" name="action" value="add_module">
                <button id="add_page_tabs_btn" type="submit">+ Add New</button>
            </form>
            <form id="add_module_tab_form" action="<?= BASE_FILE_URL ?>processes/manage_documentation.php" method="POST" class="hidden">
                <input type="hidden" name="action" value="add_module_tab">
                <input type="hidden" name="module_id" class="module_id">
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