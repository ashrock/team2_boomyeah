<?php
    session_start();
    include_once("../config/connection.php");
    include_once("../config/constants.php");
    include_once("./partial_helper.php");

    if(isset($_POST["action"])){
        switch ($_POST["action"]) {
            case "get_documentations": {
                $response_data = array("status" => false, "result" => [], "error"  => null);
                
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

                echo json_encode($response_data);
                break;
            }
            case "add_documentation":
            {
                $response_data = array("status" => false, "result" => [], "error"  => null);

                if(array_key_exists("documentation", $_POST)){
                    $documentation_data = array(
                        ...$_POST["documentation"],
                        "id" => time(),
                        "is_archived" => FALSE,
                        "is_private" => FALSE,
                        "cache_collaborators_count" => 10
                    );
            
                    $response_html = get_include_contents("../views/partials/document_block_partial.php", $documentation_data);
                    $response_data["status"] = true;
                    $response_data["result"]["html"] = $response_html;
                }

                echo json_encode($response_data);
                break;
            }
        }
    }
?>