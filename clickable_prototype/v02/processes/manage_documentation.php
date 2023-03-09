<?php
    session_start();
    include_once("../config/connection.php");
    include_once("../config/constants.php");
    include_once("./partial_helper.php");

    //load the initial data from the json file
    $sections_data_file_path = "../assets/json/sections_data.json";
    $documentation_data_file = "../assets/json/documentation_data.json";
    $sections_data = load_json_file($sections_data_file_path);
    $documentation_data = load_json_file($documentation_data_file);

    if(isset($_POST["action"])){
        $response_data = array("status" => false, "result" => [], "error"  => null);

        switch ($_POST["action"]) {
            case "get_documentations": {
                // Declare initial variables and values
                $get_documentations_html = "";

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

                //creating the html for the ajax
                if(count($filtered_documentations)){
                    foreach ($filtered_documentations as $fetch_admin_data) {
                        $get_documentations_html .= get_include_contents("../views/partials/document_block_partial.php", $fetch_admin_data);
                    }
                }
                else{
                    $message = ($_POST["is_archived"] == "{$_NO}") ? "You have no documentations yet." : "You have no archived documentations yet.";
                    $get_documentations_html = get_include_contents("../views/partials/no_documentations_partial.php", array("message" => $message));
                }
                // AJAX response
                $response_data["status"]         = true;
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
                    $response_data["status"]                = true;
                    $response_data["result"]["document_id"] = $new_data;
                    $response_data["result"]["html"]        = $get_documentations_html;
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
                    $response_data["status"]                     = true;
                    $response_data["result"]["documentation_id"] = $_POST["documentation_id"];
                    $response_data["result"]["update_type"]      = $_POST["update_type"];
                    $response_data["result"]["is_archived"]      = $_POST["update_value"];
                }
                else{
                    $response_data["error"] = "Missing required params: documentation_id and update_type.";
                }

                break;
            }
            case "duplicate_documentation": {
                // Fetch documentation
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
                    $response_data["status"]                     = true;
                    $response_data["result"]["documentation_id"] = $new_document_id;
                    $response_data["result"]["html"]             = $documentation_html;
                }
                else {
                    $response_data["error"] = "An error occurred while trying to duplicate documentation.";
                }

                break;
            }
            case "reorder_documentations": {
                //reorder the arrangement of the array based on the string $_POST['documentations_order']
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

                //update json file
                file_put_contents($documentation_data_file, json_encode($documentation_data));
                
                //AJAX response
                $response_data["status"] = true;
            }
            case "update_documentation_privacy": {
                $response_data["status"]     = true;
                $response_data["is_private"] = ((bool) $_POST["update_value"]);

                break;
            }
            case "update_documentation_data": {
                $response_data["status"] = true;

                break;
            }
            case "create_section" : {
                $new_section_data = array(
                    "id"               => time(),
                    "documentation_id" => time(),
                    "user_id"          => time(),
                    "title"            => $_POST["section_title"],
                    "description"      => "The difference between set() and append() is that if the specified key already exists, set() will overwrite all existing values with the new one, whereas append() will append the new value onto the end of the existing set of values."
                );

                array_push($sections_data["fetch_section_admin_data"], $new_section_data);
                file_put_contents($sections_data_file_path, json_encode($sections_data));
 
                $response_data["status"] = true;
                $response_data["section_id"] = $new_section_data["id"];
                $response_data["result"]["html"] = get_include_contents("../views/partials/section_block_partial.php", $new_section_data);
                break;
            }
            case "update_section" : {
                $updated_section_data = [];
                foreach($sections_data["fetch_section_admin_data"] as &$section_data){
                    if($section_data["id"] == $_POST["section_id"]){
                        $section_data["title"] = $_POST["update_value"];
                        $updated_section_data  = $section_data;
                        break;
                    }
                }  
             
                if($updated_section_data) {
                    // do something with the updated section
                    file_put_contents($sections_data_file_path, json_encode($sections_data));
                    $response_data["status"]             = true;
                    $section_data["id"]                  = $_POST["section_id"];
                    $section_data[$_POST["update_type"]] = $_POST["update_value"];
                    $response_data["result"]["html"]     = get_include_contents("../views/partials/section_block_partial.php", $updated_section_data);
                } 
                else {
                    // handle the case where no matching section is found
                    $response_data["error"] = "There's no record found.";
                }
                break;
            }
            case "duplicate_section" : {
                $duplicated_section_data = [];
                $section_index           = 0;
                foreach($sections_data["fetch_section_admin_data"] as &$section_data){
                    if($section_data["id"] == $_POST["section_id"]){
                        $new_section_data = [
                            "id"    => time(),
                            "title" => "Copy of " . $section_data["title"]
                        ];
                        array_splice($sections_data["fetch_section_admin_data"], $section_index + 1, 0, [$new_section_data]);
                        $duplicated_section_data = $new_section_data;
                        break;
                    }
                    $section_index++;
                }
                
                if($duplicated_section_data){
                    file_put_contents($sections_data_file_path, json_encode($sections_data));
                    $response_data["status"]               = true;
                    $response_data["result"]["html"]       = get_include_contents("../views/partials/section_block_partial.php", $duplicated_section_data);
                    $response_data["result"]["section_id"] = $duplicated_section_data["id"];
                }
                else{
                    $response_data["error"] = "There's no record found.";
                }
      
                break;
            }
            case "remove_section" : {
                foreach($sections_data["fetch_section_admin_data"] as $key => $section_data){
                    if($section_data["id"] == $_POST["section_id"]){
                        unset($sections_data["fetch_section_admin_data"][$key]);
                        break;
                    }
                }

                file_put_contents($sections_data_file_path, json_encode($sections_data));

                $response_data["status"]               = true;
                $response_data["result"]["section_id"] = $_POST["section_id"];
                break;
            }
            case "get_collaborators" : {
                $response_data["status"] = true;
                $collaborators_html = "";
                $collaborator_emails = array(
                    "ecaccam@village88.com",
                    "jganggangan@village88.com",
                    "jabengona@village88.com",
                    "kei.kishimoto@village88.com",
                    "eesquilon@village88.com",
                    "hnocos@village88.com",
                    "ifuncion@village88.com",
                    "vince.gurtiza@village88.com"
                );

                foreach($collaborator_emails as $collaborator_key => $collaborator_email){
                    $collaborator_data = array(
                        "collaborator_email"    => $collaborator_email,
                        "id"                    => (time() + rand()),
                        "is_owner"              => ($collaborator_key == 0),
                        "collaborator_level_id" => ((time() + rand()) % 2 == 0) ? 1 : 2
                    );
                    $collaborators_html .= get_include_contents("../views/partials/invited_user_partial.php", $collaborator_data);
                }
                $response_data["result"]["html"] = $collaborators_html;

                break;
            }
            case "add_collaborators" : {
                $response_data["status"] = true;
                $collaborators_html      = "";
                $collaborator_emails     = explode(",", $_POST["collaborator_emails"] );

                foreach($collaborator_emails as $collaborator_email){
                    $collaborator_data = array(
                        "collaborator_email"    => $collaborator_email,
                        "id"                    => (time() + rand()),
                        "is_owner"              => FALSE,
                        "collaborator_level_id" => 1
                    );
                    $collaborators_html .= get_include_contents("../views/partials/invited_user_partial.php", $collaborator_data);
                }
                $response_data["result"]["html"] = $collaborators_html;

                break;
            }
            case "update_collaborator" : {
                $collaborator_data = array(
                    "collaborator_email"    => $_POST["email"],
                    "id"                    => time(),
                    "is_owner"              => FALSE,
                    "collaborator_level_id" => 1
                );
                
                $collaborator_data["id"]                    = $_POST["invited_user_id"];
                $collaborator_data[$_POST["update_type"]]   = $_POST["update_value"];

                $response_data["status"]                    = true;
                $response_data["result"]["invited_user_id"] = $_POST["invited_user_id"];
                $response_data["result"]["html"]            = get_include_contents("../views/partials/invited_user_partial.php", $collaborator_data);
                break;
            }
            case "remove_collaborator" : {
                $response_data["status"]                    = true;
                $response_data["result"]["invited_user_id"] = $_POST["invited_user_id"];
                break;
            }
            case "reorder_sections" : {
                $response_data["status"]                   = true;
                $sections_order                            = $_POST["sections_order"];
                $response_data["result"]["sections_order"] = $sections_order;
                break;
            }
        }
    }

    echo json_encode($response_data);
?>