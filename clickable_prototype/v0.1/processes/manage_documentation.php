<?php
    session_start();
    include_once("../config/connection.php");
    include_once("../config/constants.php");
    include_once("./partial_helper.php");

    // Load initial data
    $documentation_data = [];
    $documentation_data_file = "../assets/json/documentation_data.json";
    if (file_exists($documentation_data_file)) {
        $data = file_get_contents($documentation_data_file);
        $documentation_data = json_decode($data, true);
    }

    if(isset($_POST["action"])){
        $response_data = array("status" => false, "result" => [], "error"  => null);

        switch ($_POST["action"]) {
            case "get_documentations": {
                // Declare initial variables and values
                $get_documentations_html  = "";

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
                    //remove the data base on the specified id
                    foreach ($documentation_data["fetch_admin_data"] as $key => $data) {
                        if ($data["id"] == $_POST["remove_documentation_id"]) {
                            unset($documentation_data["fetch_admin_data"][$key]);
                            break;
                        }
                    }

                    //update the data to the json file
                    file_put_contents($documentation_data_file, json_encode($documentation_data));

                    //get the number of documentations
                    $count_documentations = count(array_filter($documentation_data['fetch_admin_data'], function ($obj) {
                        return $obj['is_archived'] == 0;
                    }));

                    //determine if going to display the no documentations or archived
                    if(($_POST["remove_is_archived"] == "{$_NO}" && $count_documentations == 0) || ($_POST["remove_is_archived"] == "{$_YES}" && $_POST["archived_documentations"] == "0")){
                        $message = ($_POST["remove_is_archived"] == "{$_NO}") ? "You have no documentations yet." : "You have no archived documentations yet.";

                        $response_data["result"]["is_archived"]            = $_POST["remove_is_archived"];
                        $response_data["result"]["no_documentations_html"] = get_include_contents("../views/partials/no_documentations_partial.php", array("message" => $message));
                    }

                    //AJAX response
                    $response_data["status"]                     = true;
                    $response_data["result"]["documentation_id"] = $_POST["remove_documentation_id"];

                } 
                else {
                    $response_data["error"] = "You are not allowed to do this action!";
                }

                break;
            }
            case "create_documentation": {
                if(isset($_POST["document_title"])){
                    //Manipulate the received data
                    $document_title = escape_this_string($_POST["document_title"]);
                    $new_data = array(
                        "id"                        => count($documentation_data["fetch_admin_data"]) + 1,
                        "title"                     => $document_title,
                        "is_private"                => $_YES,
                        "is_archived"               =>  $_NO,
                        "cache_collaborators_count" => $_ZERO_VALUE
                    );

                    //update the data to the json file
                    array_push($documentation_data["fetch_admin_data"], $new_data);
                    file_put_contents($documentation_data_file, json_encode($documentation_data));

                    // Generate HTML for the updated documentation data
                    $get_documentations_html = "";
                    //pass the data to the jquery so that it updates the DOM
                    foreach($documentation_data["fetch_admin_data"] as $fetch_admin_data){
                        $get_documentations_html .= get_include_contents("../views/partials/document_block_partial.php", $fetch_admin_data);
                    }

                    //AJAX response
                    $response_data["status"] = true;
                    $response_data["result"]["document_id"] = $new_data;
                    $response_data["result"]["html"] = $get_documentations_html;
                }
                else{
                    $response_data["error"] = "Document title is required!";
                }

                break;
            }
            case "update_documentation": {
                if(isset($_POST["update_type"]) && isset($_POST["documentation_id"])){

                    $update_value = $_POST["update_value"];
                    if($_POST["update_type"] == "is_private"){
                        foreach ($documentation_data["fetch_admin_data"] as &$item) {
                            if ($item["id"] == $_POST['documentation_id']) {
                                $item["is_private"] = $update_value;
                                $updated_document = $item; 
                                break;
                            }
                        }
                        $response_data["result"]["html"] = get_include_contents("../views/partials/document_block_partial.php", $updated_document);  
                    }
                    else if($_POST["update_type"] == "is_archived" ){
                        foreach ($documentation_data["fetch_admin_data"] as &$item) {
                            if ($item["id"] == $_POST['documentation_id']) {
                                $item["is_archived"] = $update_value;
                                break;
                            }
                        }

                        $count_documentations = count(array_filter($documentation_data['fetch_admin_data'], function ($obj) {
                            return $obj['is_archived'] == 0;
                        }));

                        if(($update_value == "{$_NO}" && $_POST["archived_documentations"] == "0") || ($update_value == "{$_YES}" && $count_documentations == 0)){
                            $message = ($update_value == "{$_NO}") ? "You have no archived documentations yet." : "You have no documentations yet.";
    
                            $response_data["result"]["is_archived"]            = $update_value;
                            $response_data["result"]["no_documentations_html"] = get_include_contents("../views/partials/no_documentations_partial.php", array("message" => $message));
                        }
                      
                    }
                    else if($_POST["update_type"] == "title" ){
                        foreach ($documentation_data["fetch_admin_data"] as &$item) {
                            if ($item["id"] == $_POST['documentation_id']) {
                                $item["title"] = $update_value;
                                break;
                            }
                        }
                    }

                    //update the data to the json file
                    file_put_contents($documentation_data_file, json_encode($documentation_data));
                    //AJAX response
                    $response_data["status"] = true;
                    $response_data["result"]["documentation_id"] = $_POST["documentation_id"];
                    $response_data["result"]["update_type"] = $_POST["update_type"];
                    $response_data["result"]["is_archived"] = $_POST["update_value"];

                }
                else{
                    $response_data["error"] = "Missing required params: documentation_id and update_type.";
                }

                break;
            }
            case "duplicate_documentation": {
                $documentation_id = $_POST['documentation_id'];

                //find the document data to be duplicated base on its id
                $to_be_duplicated_block = array_filter($documentation_data["fetch_admin_data"], function($item) use ($documentation_id) {
                    return $item["id"] == $documentation_id;
                });
                
                if (!empty($to_be_duplicated_block)) {
                    //constructing the data to be duplicated
                    $to_be_duplicated_block     = array_values($to_be_duplicated_block)[0];
                    $new_document_id            = count($documentation_data["fetch_admin_data"]) + 1;
                    $new_title                  = "Copy of " . $to_be_duplicated_block["title"];
                    $new_document_data          = array_merge($to_be_duplicated_block, [
                        "id"                    => $new_document_id,
                        "title"                 => $new_title
                    ]);

                    //pass the data to the jquery so that it updates the DOM
                    $documentation_html = get_include_contents("../views/partials/document_block_partial.php", $new_document_data);
                   
                    //update json file
                    array_push($documentation_data["fetch_admin_data"], $new_document_data);
                    file_put_contents($documentation_data_file, json_encode($documentation_data));

                    //AJAX response
                    $response_data["status"] = true;
                    $response_data["result"]["documentation_id"] = $new_document_id;
                    $response_data["result"]["html"] = $documentation_html;
                } 
                else {
                    $response_data["error"] = "An error occurred while trying to duplicate documentation.";
                }

                break;
            }
            case "reorder_documentations": {

                $documentations_order = explode(",", $_POST['documentations_order']);
                $reordered_documentations_array = array();
                foreach ($documentations_order as $order) {
                    foreach ($documentation_data["fetch_admin_data"] as $item) {
                        if ($item["id"] == $order) {
                            $reordered_documentations_array[] = $item;
                            break;
                        }
                    }
                }
                $documentation_data["fetch_admin_data"] = $reordered_documentations_array;
                file_put_contents($documentation_data_file, json_encode($documentation_data));
                
                $response_data["status"] = true;
            }
        }
    }

    echo json_encode($response_data);
?> 