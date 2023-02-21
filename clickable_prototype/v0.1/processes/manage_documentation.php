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
            case "create_documentation": {
                $response_data = array("status" => false, "result" => [], "error"  => null);

                if(isset($_POST["document_title"])){
                    $document_title = escape_this_string($_POST["document_title"]);

                    $insert_document_record = run_mysql_query("
                        INSERT INTO documentations (user_id, workspace_id, title, is_archived, is_private, cache_collaborators_count, created_at, updated_at) 
                        VALUES ({$_SESSION["user_id"]}, {$_SESSION["workspace_id"]}, '{$document_title}', {$_NO}, {$_YES}, {$_ZERO_VALUE}, NOW(), NOW())
                    ");

                    if($insert_document_record != $_ZERO_VALUE){
                        $workspace = fetch_record("SELECT documentations_order FROM workspaces WHERE id = {$_SESSION["workspace_id"]};");
                        $new_workspace_order = $workspace["documentations_order"].','. $insert_document_record;

                        $update_workspace_docs_order = run_mysql_query("UPDATE workspaces SET documentations_order = '{$new_workspace_order}' WHERE id = {$_SESSION["workspace_id"]}");

                        if($update_workspace_docs_order){
                            $response_data["status"] = true;
                            $response_data["result"]["document_id"] = $insert_document_record;
                        }
                    }
                }
                else{
                    $response_data["error"] = "Document title is required!";
                }

                echo json_encode($response_data);
                break;
            }
        }
    }
?>