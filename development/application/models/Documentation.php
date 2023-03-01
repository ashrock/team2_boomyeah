<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Documentation extends CI_Model {
        public function getDocumentation($documentation_id){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $get_documentation = $this->db->query("SELECT id, title, description, section_ids_order, is_archived, is_private FROM documentations WHERE id = ?;", $documentation_id);

                if($get_documentation->num_rows()){
                    $response_data["result"] = $get_documentation->result_array()[0];
                }

                $response_data["status"] = true;
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        public function getDocumentations($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                // ! Binding an array value encloses it in a parenthesis which causes an error

                $where_conditions = "is_archived = ? ";
                $bind_params      = array($params["workspace_id"], $params["is_archived"]);
                $order_by_clause  = "";

                if($params["user_level_id"] == USER_LEVEL["USER"]){
                    $where_conditions .= "AND (is_private = ?  OR id IN (SELECT documentation_id FROM collaborators WHERE user_id = ?)) ";
                    array_push($bind_params, TRUE_VALUE, $_SESSION["user_id"]);
                }

                /* Add ORDER BY if documentation_ids_order is no null or empty */
                if($params["documentation_ids_order"]){
                    $order_by_clause = "ORDER BY FIELD (id, {$params["documentation_ids_order"]})";
                }

                $get_documentations = $this->db->query("SELECT id, title, is_archived, is_private, cache_collaborators_count
                    FROM documentations
                    WHERE workspace_id = ? AND {$where_conditions}
                    {$order_by_clause};", $bind_params
                );

                if($get_documentations->num_rows()){
                    $response_data["result"] = $get_documentations->result_array();
                }
                
                $response_data["status"] = true;
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        public function addDocumentations($params){
            $response_data = array("status" => false, "result" => [], "error" => null);

            try {
                $insert_document_record = $this->db->query("
                    INSERT INTO documentations (user_id, workspace_id, title, is_archived, is_private, cache_collaborators_count, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())",
                    array($params["user_id"], $params["workspace_id"], $params["title"], NO, YES, ZERO_VALUE)
                );

                $new_documentation_id = $this->db->insert_id($insert_document_record);

                if($new_documentation_id > ZERO_VALUE){
                    $workspace = $this->Workspace->getDocumentationsOrder($params["workspace_id"]);
                    
                    if(!isset($params["is_duplicate"])){
                        $new_documents_order = (strlen($workspace["result"]["documentation_ids_order"])) ? $workspace["result"]["documentation_ids_order"].','. $new_documentation_id : $new_documentation_id;
                    }
                    else{
                        $new_documents_order = explode(",", $workspace["result"]["documentation_ids_order"]);
    
                        for($document_index=0; $document_index < count($new_documents_order); $document_index++){
                            if($params["documentation_id"] == (int)$new_documents_order[$document_index]){
                                array_splice($new_documents_order, $document_index + 1, 0, "{$new_documentation_id}");
                            }
                        }
        
                        // Convert array to comma-separated string and update new_documents_order of new_documents_order
                        $new_documents_order = implode(",", $new_documents_order);
                    }

                    $update_workspace_docs_order = $this->db->query("UPDATE workspaces SET documentation_ids_order = ? WHERE id = ?", array( $new_documents_order, $params["workspace_id"]));

                    if($update_workspace_docs_order){
                        $response_data["status"] = true;
                        $response_data["result"] = array("documentation_id" => $new_documentation_id);
                    }
                }
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        public function updateDocumentations($params){
            $response_data = array("status" => false, "result" => [], "error" => null);

            try {
                $document = $this->db->query("SELECT id FROM documentations WHERE id = ?", $params["documentation_id"])->row();
                
                if(isset($document->{'id'})){
                    if( in_array($params["update_type"], ["title", "is_archived", "is_private"]) ){
                        $update_document = $this->db->query("UPDATE documentations SET {$params["update_type"]} = ? WHERE id = ?", array($params["update_value"], $params["documentation_id"]) );
                      
                        if($update_document){
                            $updated_document = $this->db->query("SELECT id, title, is_archived, is_private, cache_collaborators_count FROM documentations WHERE id = ?", $params["documentation_id"])->row();

                            $response_data["status"] = true;
                            $response_data["result"] = array(
                                "documentation_id" => $updated_document->{'id'},
                                "update_type" => $params["update_type"],
                            );

                            // if($params["update_type"] == "is_private"){
                            //     $response_data["result"]["html"] = get_include_contents("../views/partials/document_block_partial.php", $updated_document);
                            // }
                            // elseif($_POST["update_type"] == "is_archived" ){
                            //     $workspace = fetch_record("SELECT documentations_order FROM workspaces WHERE id = {$_SESSION["workspace_id"]}");
                            //     $documentation_order_array = explode(",", $workspace["documentations_order"]);
                            //     $new_documents_order = NULL;

                            //     if($_POST["update_value"] == $_YES){
                            //         if (($key = array_search($_POST["documentation_id"], $documentation_order_array)) !== false) {
                            //             unset($documentation_order_array[$key]);
                            //             $documentations_count = count($documentation_order_array);
                            //         }

                            //         $new_documents_order = ($documentations_count) ? implode(",", $documentation_order_array) : "";
                            //     }
                            //     else {
                            //         $new_documents_order = (strlen($workspace["documentations_order"])) ? $workspace["documentations_order"].','. $_POST["documentation_id"] : $_POST["documentation_id"];
                            //     }

                            //     $update_workspace = run_mysql_query("UPDATE workspaces SET documentations_order = '{$new_documents_order}' WHERE id = {$_SESSION["workspace_id"]}");

                            //     if(($update_value == "{$_NO}" && $_POST["archived_documentations"] == "0") || ($update_value == "{$_YES}" && !$documentations_count)){
                            //         $message = ($update_value == "{$_NO}") ? "You have no archived documentations yet." : "You have no documentations yet.";
            
                            //         $response_data["result"]["is_archived"]            = $update_value;
                            //         $response_data["result"]["no_documentations_html"] = get_include_contents("../views/partials/no_documentations_partial.php", array("message" => $message));
                            //     }
                            // }
                        }
                    }
                }
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        public function duplicateDocumentation($documentation_id){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $this->db->trans_start();
                $get_documentation = $this->getDocumentation($documentation_id);

                if($get_documentation["status"]){
                    $duplicate_title  = "Copy of {$get_documentation['result']['title']}";

                    // Create new documentation
                    $duplicate_documentation = $this->addDocumentations(array(
                        "is_duplicate"     => true,
                        "documentation_id" => $documentation_id,
                        "user_id"          => $_SESSION["user_id"],
                        "workspace_id"     => $_SESSION["workspace_id"],
                        "title"		       => $duplicate_title
                    ));;

                    if($duplicate_documentation["status"]){
                        $response_data["status"]                     = true;
                        $response_data["result"]["documentation_id"] = $documentation_id;
                        $response_data["result"]["html"]             = $this->load->view(
                            "partials/document_block_partial.php",
                            array(
                                "id"                        => $duplicate_documentation["result"]["documentation_id"],
                                "title"                     => $duplicate_title,
                                "is_private"                => TRUE_VALUE,
                                "is_archived"               => FALSE_VALUE,
                                "cache_collaborators_count" => ZERO_VALUE
                            ), 
                            true
                        );
                    }
                    else{
                        throw new Exception($duplicate_documentation["error"]);
                    }

                    $this->db->trans_complete();
                }
            }
            catch (Exception $e) {
			    $this->db->trans_rollback();
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        public function deleteDocumentation($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $this->db->trans_start();

                $delete_collaborators = $this->Collaborator->deleteCollaborators(array("documentation_id" => $params["remove_documentation_id"]));

                if($delete_collaborators["status"]){
                    $delete = $this->db->query("DELETE FROM documentations WHERE id = ?;", $params["remove_documentation_id"]);
    
                    if($this->db->affected_rows()){
                        /* Remove remove_documentation_id in documentations_order and update documentations_order in workpsaces table */
                        $documentations_order = $this->Workspace->getDocumentationsOrder($_SESSION["workspace_id"]);
    
                        if($documentations_order["status"]){
                            $documentations_order = explode(",", $documentations_order["result"]["documentation_ids_order"]);
                            $documentations_count = count($documentations_order);
                            $documentation_index  = array_search($params["remove_documentation_id"], $documentations_order);
                            
                            if($documentation_index !== FALSE){
                                unset($documentations_order[$documentation_index]);
                                $documentations_count = count($documentations_order);
        
                                $documentations_order = ($documentations_count) ? implode(",", $documentations_order) : "";
                                $update_workpsace = $this->Workspace->updateDocumentationsIdsOrder(array("documentations_order" => $documentations_order, "workspace_id" => $_SESSION["workspace_id"]));
    
                                if(!$update_workpsace["status"]){
                                    throw new Exception($update_workpsace["error"]);
                                }
                            }
    
                            if(($params["remove_is_archived"] == FALSE_VALUE && !$documentations_count) || ($params["remove_is_archived"] == TRUE_VALUE && $params["archived_documentations"] == "0")){
                                $message = ($params["remove_is_archived"] == FALSE_VALUE) ? "You have no documentations yet." : "You have no archived documentations yet.";
        
                                $response_data["result"]["is_archived"]            = $params["remove_is_archived"];
                                $response_data["result"]["no_documentations_html"] = $this->load->view('partials/no_documentations_partial.php', array('message' => $message), true);
                            }
                            
                            $response_data["status"] = true;
                            $response_data["result"]["documentation_id"] = $params["remove_documentation_id"];
                            $this->db->trans_complete();
                        }
                        else{
                            throw new Exception($documentations_order["error"]);
                        }
                    }
                }
                else{
                    throw new Exception($delete_collaborators["error"]);
                }
            }
            catch (Exception $e) {
			    $this->db->trans_rollback();
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }
                    
    }
?>