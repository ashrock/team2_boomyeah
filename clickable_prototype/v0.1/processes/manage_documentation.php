<?php
    session_start();
    include_once("../config/connection.php");
    include_once("../config/constants.php");
    include_once("./partial_helper.php");

    if(isset($_POST["action"])){
        $response_data = array("status" => false, "result" => [], "error"  => null);

        switch ($_POST["action"]) {
            case "get_documentations": {
                // Declare initial variables and values
                $get_documentations_html  = "";
                $get_documentations_query = "SELECT id, title, is_archived, is_private, cache_collaborators_count FROM documentations WHERE workspace_id = {$_SESSION["workspace_id"]}";

                // Modify initial query
                if($_POST["is_archived"] == "{$_NO}"){
                    $documentations_order = fetch_record("SELECT documentations_order FROM workspaces WHERE id = {$_SESSION["workspace_id"]};");
                    $documentations_order = $documentations_order["documentations_order"];

                    $get_documentations_query .= " AND is_archived = {$_POST["is_archived"]} ORDER BY FIELD (id, {$documentations_order});";
                } else {
                    $get_documentations_query .= " AND is_archived = {$_POST["is_archived"]};";
                }

                // Run MySQL query
                $get_documentations = fetch_all($get_documentations_query);

                // Generate HTML
                for($documentations_index = 0; $documentations_index < count($get_documentations); $documentations_index++){
                    if($_POST["is_archived"] == "{$_YES}"){
                        $get_documentations[$documentations_index]["is_archived_view"] = $_YES;
                    }

                    $get_documentations_html .= get_include_contents("../views/partials/document_block_partial.php", $get_documentations[$documentations_index]);
                }
    
                $response_data["status"] = true;
                $response_data["result"]["html"] = $get_documentations_html;

                break;
            }
            case "remove_documentation": {
                if($_SESSION["user_level_id"] == $_USER_LEVEL["admin"]){
                    run_mysql_query("DELETE FROM documentations WHERE id = {$_POST["remove_documentation_id"]};");

                    /* Remove remove_documentation_id in documentations_order and update documentations_order in workpsaces table */
                    $documentations_order = fetch_record("SELECT documentations_order FROM workspaces WHERE id = {$_SESSION["workspace_id"]};");
                    $documentations_order = explode(",", $documentations_order["documentations_order"]);
                    
                    $documentation_index = array_search($_POST["remove_documentation_id"], $documentations_order);
                    
                    if($documentation_index !== FALSE){
                        unset($documentations_order[$documentation_index]);

                        $documentations_order = implode(",", $documentations_order);
                        run_mysql_query("UPDATE workspaces SET documentations_order = '{$documentations_order}' WHERE id = {$_SESSION["workspace_id"]};");

                        $response_data["status"] = true;
                        $response_data["result"]["documentation_id"] = $_POST["remove_documentation_id"];
                    }
                }

                break;
            }
        }
    }

    echo json_encode($response_data);
?>