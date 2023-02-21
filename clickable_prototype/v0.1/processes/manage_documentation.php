<?php
    session_start();
    include_once("../config/connection.php");
    include_once("../config/constants.php");
    include_once("./partial_helper.php");

    if(isset($_POST["process_type"])){
        switch ($_POST["process_type"]) {
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
            case "get_documentations":
            {
                $documentations_html = "";
                $response_data = array("status" => false, "result" => [], "error"  => null);
    
                if($_POST["is_archived"] == "{$_NO}"){
    
                } else if($_POST["is_archived"] == "{$_YES}") {
                    $archived_documentations = fetch_all("SELECT id, title, is_archived, is_private, cache_collaborators_count
                        FROM documentations
                        WHERE workspace_id = {$_SESSION["workspace_id"]} AND is_archived = {$_YES};
                    ");
    
                    for($documentations_index = 0; $documentations_index < count($archived_documentations); $documentations_index++){
                        $archived_documentations[$documentations_index]["is_archived_view"] = $_YES;
                        $documentations_html .= get_include_contents("../views/partials/document_block_partial.php", $archived_documentations[$documentations_index]);
                    }
                }
    
                $response_data["status"] = true;
                $response_data["result"]["html"] = $documentations_html;

                echo json_encode($response_data);
                break;
            }
        }
    }
?>