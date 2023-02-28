<?php
    session_start();
    include_once("../config/connection.php");
    include_once("../config/constants.php");
    include_once("./partial_helper.php");

    // Load initial data
    $documentation_data = [];
    $initial_data_file = "../assets/json/documentation_data.json";
    if (file_exists($initial_data_file)) {
        $data = file_get_contents($initial_data_file);
        $documentation_data = json_decode($data, true);
    }

    if(isset($_POST["action"])){
        $response_data = array("status" => false, "result" => [], "error"  => null);

        switch ($_POST["action"]) {
            case "get_documentations": {
                // Declare initial variables and values
                $get_documentations_html  = "";
                // $get_documentations_query = "SELECT id, title, is_archived, is_private, cache_collaborators_count FROM documentations WHERE workspace_id = {$_SESSION["workspace_id"]}";

                // // Modify initial query
                // if($_POST["is_archived"] == "{$_NO}"){
                //     $documentations_order = fetch_record("SELECT documentations_order FROM workspaces WHERE id = {$_SESSION["workspace_id"]};");
                //     $documentations_order = $documentations_order["documentations_order"];

                //     $get_documentations_query .= " AND is_archived = {$_POST["is_archived"]} ORDER BY FIELD (id, {$documentations_order});";
                // } else {
                //     $get_documentations_query .= " AND is_archived = {$_POST["is_archived"]};";
                // }

                // // Run MySQL query
                // $get_documentations = fetch_all($get_documentations_query);

                // if(count($get_documentations)){
                //     // Generate HTML
                //     for($documentations_index = 0; $documentations_index < count($get_documentations); $documentations_index++){
                //         $get_documentations_html .= get_include_contents("../views/partials/document_block_partial.php", $get_documentations[$documentations_index]);
                //     }
                // }
                // else{
                //     $message = ($_POST["is_archived"] == "{$_NO}") ? "You have no documentations yet." : "You have no archived documentations yet.";
                //     $get_documentations_html = get_include_contents("../views/partials/no_documentations_partial.php", array("message" => $message));
                // }

                //manipulating the initial data
                $filtered_documentations = [];
                if($_POST["is_archived"] == "{$_NO}") {
                    $filtered_documentations = array_filter($documentation_data["fetch_admin_data"], function($data) {
                        return $data["is_archived"] == 0;
                    });
                }
                else {
                    $filtered_documentations = array_filter($documentation_data["fetch_admin_data"], function($data) {
                        return $data["is_archived"] == 1;
                    });
                }

                if(count($filtered_documentations)){
                    foreach ($filtered_documentations as $fetch_admin_data) {
                        $get_documentations_html .= get_include_contents("../views/partials/document_block_partial.php", $fetch_admin_data);
                    }
                }
                else{
                    $message = ($_POST["is_archived"] == "{$_NO}") ? "You have no documentations yet." : "You have no archived documentations yet.";
                    $get_documentations_html = get_include_contents("../views/partials/no_documentations_partial.php", array("message" => $message));
                }
        
                $response_data["status"] = true;
                $response_data["result"]["html"] = $get_documentations_html;

                break;
            }
            case "remove_documentation": {
                if($_SESSION["user_level_id"] == $_USER_LEVEL["admin"]){

                    foreach ($documentation_data["fetch_admin_data"] as $key => $data) {
                        if ($data["id"] == $_POST["remove_documentation_id"]) {
                            unset($documentation_data["fetch_admin_data"][$key]);
                            break;
                        }
                    }
                    $response_data["status"]                     = true;
                    $response_data["result"]["documentation_id"] = $_POST["remove_documentation_id"];

                    // run_mysql_query("DELETE FROM documentations WHERE id = {$_POST["remove_documentation_id"]};");

                    // /* Remove remove_documentation_id in documentations_order and update documentations_order in workpsaces table */
                    // $documentations_order = fetch_record("SELECT documentations_order FROM workspaces WHERE id = {$_SESSION["workspace_id"]};");
                    // $documentations_order = explode(",", $documentations_order["documentations_order"]);
                    
                    // $documentation_index = array_search($_POST["remove_documentation_id"], $documentations_order);
                    
                    // if($documentation_index !== FALSE){
                    //     unset($documentations_order[$documentation_index]);
                    //     $documentations_count = count($documentations_order);

                    //     $documentations_order = ($documentations_count) ? implode(",", $documentations_order) : "";
                    //     run_mysql_query("UPDATE workspaces SET documentations_order = '{$documentations_order}' WHERE id = {$_SESSION["workspace_id"]};");
                    // }

                    // $response_data["status"] = true;
                    // $response_data["result"]["documentation_id"] = $_POST["remove_documentation_id"];
                    
                    // if(($_POST["remove_is_archived"] == "{$_NO}" && !$documentations_count) || ($_POST["remove_is_archived"] == "{$_YES}" && $_POST["archived_documentations"] == "0")){
                    //     $message = ($_POST["remove_is_archived"] == "{$_NO}") ? "You have no documentations yet." : "You have no archived documentations yet.";

                    //     $response_data["result"]["is_archived"]            = $_POST["remove_is_archived"];
                    //     $response_data["result"]["no_documentations_html"] = get_include_contents("../views/partials/no_documentations_partial.php", array("message" => $message));
                    // }
                } 
                else {
                    $response_data["error"] = "You are not allowed to do this action!";
                }

                break;
            }
            case "create_documentation": {
                if(isset($_POST["document_title"])){
                    $document_title = escape_this_string($_POST["document_title"]);
                 
                    $new_data = array(
                        "id" => $_SESSION["user_id"],
                        "title" => $document_title,
                        "is_private" => $_YES,
                        "is_archived" =>  $_NO,
                        "cache_collaborators_count" => $_ZERO_VALUE
                    );
                    array_push($documentation_data["fetch_admin_data"], $new_data);
                    //save the data to the json file
                    file_put_contents($initial_data_file, json_encode($documentation_data));
                    $get_documentations_html = "";
                    $filtered_documentations = [];
                    $filtered_documentations = array_filter($documentation_data["fetch_admin_data"], function($data) {
                        return $data["is_archived"] == 0;
                    });
                    if(count($filtered_documentations)){
                        foreach ($filtered_documentations as $fetch_admin_data) {
                            $get_documentations_html .= get_include_contents("../views/partials/document_block_partial.php", $fetch_admin_data);
                        }
                    }
                    $response_data["status"] = true;
                    $response_data["result"]["document_id"] = $new_data;
                    $response_data["result"]["html"] = $get_documentations_html;

                    // $insert_document_record = run_mysql_query("
                    //     INSERT INTO documentations (user_id, workspace_id, title, is_archived, is_private, cache_collaborators_count, created_at, updated_at) 
                    //     VALUES ({$_SESSION["user_id"]}, {$_SESSION["workspace_id"]}, '{$document_title}', {$_NO}, {$_YES}, {$_ZERO_VALUE}, NOW(), NOW())
                    // ");

                    // if($insert_document_record != $_ZERO_VALUE){
                    //     $workspace = fetch_record("SELECT documentations_order FROM workspaces WHERE id = {$_SESSION["workspace_id"]};");
                    //     $new_documents_order = (strlen($workspace["documentations_order"])) ? $workspace["documentations_order"].','. $insert_document_record : $insert_document_record;

                    //     $update_workspace_docs_order = run_mysql_query("UPDATE workspaces SET documentations_order = '{$new_documents_order}' WHERE id = {$_SESSION["workspace_id"]}");

                    //     if($update_workspace_docs_order){
                    //         $response_data["status"] = true;
                    //         $response_data["result"]["document_id"] = $insert_document_record;
                    //     }
                    // }
                }
                else{
                    $response_data["error"] = "Document title is required!";
                }

                break;
            }
            case "update_documentation": {
                if(isset($_POST["update_type"]) && isset($_POST["documentation_id"])){

                    if($_POST["update_type"] == "is_private"){
                        var_dump($_POST);
                        // $response_data["result"]["html"] = get_include_contents("../views/partials/document_block_partial.php", $updated_document);
                    }

                    $response_data["status"] = true;
                    $response_data["result"]["documentation_id"] = $_POST["documentation_id"];
                    $response_data["result"]["update_type"] = $_POST["update_type"];
                    $response_data["result"]["is_archived"] = $_POST["update_value"];

                    // $document = fetch_record("SELECT id FROM documentations WHERE id = {$_POST["documentation_id"]}");

                    // if(count($document) > $_ZERO_VALUE){
                    //     if( in_array($_POST["update_type"], ["title", "is_archived", "is_private"]) ){
                    //         $update_value = escape_this_string($_POST["update_value"]);
                    //         $update_document = run_mysql_query("UPDATE documentations SET {$_POST["update_type"]} = '{$update_value}' WHERE id = {$_POST["documentation_id"]}");
                            
                    //         if($update_document){
                    //             $updated_document = fetch_record("SELECT id, title, is_archived, is_private, cache_collaborators_count FROM documentations WHERE id = {$_POST["documentation_id"]}");
                    //             $response_data["status"] = true;
                    //             $response_data["result"]["documentation_id"] = $updated_document["id"];
                    //             $response_data["result"]["update_type"] = $_POST["update_type"];
                    //             $response_data["result"]["is_archived"] = $update_value;

                    //             if($_POST["update_type"] == "is_private"){
                    //                 $response_data["result"]["html"] = get_include_contents("../views/partials/document_block_partial.php", $updated_document);
                    //             }
                    //             elseif($_POST["update_type"] == "is_archived" ){
                    //                 $workspace = fetch_record("SELECT documentations_order FROM workspaces WHERE id = {$_SESSION["workspace_id"]}");
                    //                 $documentation_order_array = explode(",", $workspace["documentations_order"]);
                    //                 $new_documents_order = NULL;

                    //                 if($_POST["update_value"] == $_YES){
                    //                     if (($key = array_search($_POST["documentation_id"], $documentation_order_array)) !== false) {
                    //                         unset($documentation_order_array[$key]);
                    //                         $documentations_count = count($documentation_order_array);
                    //                     }

                    //                     $new_documents_order = ($documentations_count) ? implode(",", $documentation_order_array) : "";
                    //                 }
                    //                 else {
                    //                     $new_documents_order = (strlen($workspace["documentations_order"])) ? $workspace["documentations_order"].','. $_POST["documentation_id"] : $_POST["documentation_id"];
                    //                 }

                    //                 $update_workspace = run_mysql_query("UPDATE workspaces SET documentations_order = '{$new_documents_order}' WHERE id = {$_SESSION["workspace_id"]}");

                    //                 if(($update_value == "{$_NO}" && $_POST["archived_documentations"] == "0") || ($update_value == "{$_YES}" && !$documentations_count)){
                    //                     $message = ($update_value == "{$_NO}") ? "You have no archived documentations yet." : "You have no documentations yet.";
                
                    //                     $response_data["result"]["is_archived"]            = $update_value;
                    //                     $response_data["result"]["no_documentations_html"] = get_include_contents("../views/partials/no_documentations_partial.php", array("message" => $message));
                    //                 }
                    //             }
                    //         }
                    //     }
                    // }
                }
                else{
                    $response_data["error"] = "Missing required params: documentation_id and update_type.";
                }

                break;
            }
            case "duplicate_documentation": {
                // Fetch documentation
                // $documentation_id     = (int)$_POST['documentation_id'];
                // $get_documentation    = fetch_record("SELECT id, title, description, sections_order, is_archived, is_private FROM documentations WHERE id = {$documentation_id};");
                // $document_title       = escape_this_string($get_documentation['title']);
                // $document_description = escape_this_string($get_documentation['description']);

                $to_be_duplicated_block = array_filter($documentation_data["fetch_admin_data"], function($item) {
                    return $item["id"] == $_POST['documentation_id'];
                });
                $to_be_duplicated_block[0]["title"] = "Copy of " . $to_be_duplicated_block[0]["title"];
                $documentation_html = get_include_contents("../views/partials/document_block_partial.php", $to_be_duplicated_block[0] );
        
                $response_data["status"]                     = true;
                $response_data["result"]["documentation_id"] = $_POST['documentation_id'];
                $response_data["result"]["html"]             = $documentation_html;

                // // Create new documentation
                // $duplicate_documentation = run_mysql_query("INSERT INTO documentations (user_id, workspace_id, title, description, sections_order, is_archived, is_private, cache_collaborators_count, created_at, updated_at) 
                //     VALUES ({$_SESSION['user_id']}, {$_SESSION['workspace_id']}, 'Copy of {$document_title}', '{$document_description}', '{$get_documentation['sections_order']}', 
                //     {$get_documentation['is_archived']}, {$get_documentation['is_private']}, {$_ZERO_VALUE}, NOW(), NOW());
                // ");

                // if($duplicate_documentation){
                //     // TODO: Create sections, pages, and tabs
                //     // Check if sections_order exists
                //         // Fetch sections and its pages
                //             // Check page's tabs_order exists
                //                 // Fetch tabs
                //                 // END
                //             // END
                //         // END
                //     // END
    
                //     // Get documentations_order and insert newly created documentation_id
                //     $get_workspace        = fetch_record("SELECT documentations_order FROM workspaces WHERE id = {$_SESSION['workspace_id']};");
                //     $documentations_order = explode(",", $get_workspace["documentations_order"]);
    
                //     for($document_index=0; $document_index < count($documentations_order); $document_index++){
                //         if($documentation_id == $documentations_order[$document_index]){
                //             array_splice($documentations_order, $document_index + 1, 0, "{$duplicate_documentation}");
                //         }
                //     }
    
                //     // Convert array to comma-separated string and update documentations_order of documentations_order
                //     $documentations_order = implode(",", $documentations_order);
                //     $update_documentations_order = run_mysql_query("UPDATE workspaces SET documentations_order = '{$documentations_order}' WHERE id = {$_SESSION['workspace_id']};");
    
                //     if($update_documentations_order){
                //         // Fetch newly created documentation and generate html
                //         $get_documentation  = fetch_record("SELECT id, title, is_archived, is_private, cache_collaborators_count FROM documentations WHERE id = {$duplicate_documentation};");
                //         $documentation_html = get_include_contents("../views/partials/document_block_partial.php", $get_documentation);
        
                //         $response_data["status"]                     = true;
                //         $response_data["result"]["documentation_id"] = $duplicate_documentation;
                //         $response_data["result"]["html"]             = $documentation_html;
                //     }
                //     else {
                //         $response_data["error"] = "An error occurred while trying to update your workspace.";
                //     }
                // }
                // else {
                //     $response_data["error"] = "An error occurred while trying to duplicate documentation.";
                // }

                break;
            }
            case "reorder_documentations": {
                run_mysql_query("UPDATE workspaces SET documentations_order = '{$_POST['documentations_order']}' WHERE id = {$_SESSION["workspace_id"]}");

                $response_data["status"] = true;
            }
        }
    }

    echo json_encode($response_data);
?>