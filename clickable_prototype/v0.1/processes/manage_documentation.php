<?php
    session_start();
    include_once("../config/connection.php");
    include_once("../config/constants.php");

    switch ($_POST["process_type"]) {
        case "get_documentations":
            $documentations_html = "";

            if($_POST["document_type"] == "active"){

            } else if($_POST["document_type"] == "archived") {
                $archived_documentations = fetch_all("SELECT id, title, is_archived, is_private, cache_collaborators_count
                    FROM documentations
                    WHERE workspace_id = {$_SESSION["workspace_id"]} AND is_archived = {$_YES};
                ");

                for($documentations_index = 0; $documentations_index < count($archived_documentations); $documentations_index++){
                    $archived_doc = $archived_documentations[$documentations_index];

                    $documentations_html .= "
                        <div id='document_{$archived_doc["id"]}' class='document_block'>
                            <div class='document_details'>
                                <input type='text' name='document_title' value='{$archived_doc["title"]}' id='' class='document_title' readonly>
                    ";

                    if($archived_doc["is_private"] == "{$_YES}"){
                        $documentations_html .= "<button class='invite_collaborators_btn modal-trigger archived_disabled' href='#modal1'> {$archived_doc["cache_collaborators_count"]}</button>
                            </div>
                            <div class='document_controls'>
                                <button class='access_btn modal-trigger set_privacy_btn archived_disabled' href='#confirm_to_public' data-document_id='{$archived_doc["id"]} data-document_privacy='private'></button>";
                    } else {
                        $documentations_html .= "
                            </div>
                            <div class='document_controls nasa_else'>
                        ";
                    }

                    $documentations_html .= "
                                <button class='more_action_btn dropdown-trigger' data-target='document_more_actions_{$archived_doc["id"]}'>⁝</button>
                                <ul id='document_more_actions_{$archived_doc["id"]}' class='dropdown-content more_action_list_private'>
                                    <li><a href='#confirm_to_archive' class='archive_icon modal-trigger archive_btn' data-document_id='{$archived_doc["id"]}' data-archived_doc_action='archive'>Unarchive</a></li>
                                    <li class='divider' tabindex='-1'></li>
                                    <li><a href='#confirm_to_remove' class='remove_icon modal-trigger remove_btn' data-document_id='{$archived_doc["id"]}' data-archived_doc_action='remove'>Remove</a></li>
                                </ul>
                            </div>
                        </div>
                    ";
                }
            }

            echo $documentations_html;
            break;
    }
?>