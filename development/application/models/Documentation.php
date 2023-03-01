<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Documentation extends CI_Model {
        public function getDocumentations($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                // ! Binding an array value encloses it in a parenthesis which causes an error

                $where_conditions = "is_archived = ? ";
                $bind_params      = array($params["workspace_id"], $params["is_archived"]);

                if($params["user_level_id"] == USER_LEVEL["USER"]){
                    $where_conditions .= "AND (is_private = ?  OR id IN (SELECT documentation_id FROM collaborators WHERE user_id = ?)) ";
                    array_push($bind_params, TRUE_VALUE, $_SESSION["user_id"]);
                }
                
                $documentation_order = ($params["is_archived"] || $params["documentation_ids_order"] == null) ? "" : "ORDER BY FIELD (id, {$params["documentation_ids_order"]})";

                $get_documentations = $this->db->query("SELECT id, title, is_archived, is_private, cache_collaborators_count
                    FROM documentations
                    WHERE workspace_id = ? AND {$where_conditions}
                    $documentation_order", $bind_params
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
                    $workspace = $this->db->query("SELECT documentation_ids_order FROM workspaces WHERE id = ?", $params["workspace_id"])->row();
                    $new_documents_order = (strlen($workspace->{'documentation_ids_order'})) ? $workspace->{'documentation_ids_order'}.','. $new_documentation_id : $new_documentation_id;

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
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $document = $this->db->query("SELECT id FROM documentations WHERE id = ?", $params["documentation_id"])->row();
                
                if(isset($document->{'id'})){
                    if( in_array($params["update_type"], ["title", "is_archived", "is_private"]) ){
                        $update_document = $this->db->query("UPDATE documentations SET {$params["update_type"]} = ? WHERE id = ?", array($params["update_value"], $params["documentation_id"]) );
                      
                        if($update_document){
                            $updated_document = $this->db->query("SELECT id, title, is_archived, is_private, cache_collaborators_count FROM documentations WHERE id = ?", $params["documentation_id"])->row();

                            $response_data["status"] = true;
                            $response_data["result"]["documentation_id"] = $updated_document->{'id'};
                            $response_data["result"]["update_type"]      = $params["update_type"];

                            if($params["update_type"] == "is_private"){
                                $response_data["result"]["updated_document"] = $updated_document;
                            }
                            elseif($params["update_type"] == "is_archived" ){
                                $workspace = $this->db->query("SELECT documentation_ids_order FROM workspaces WHERE id = ?", $params["workspace_id"])->row();
                                $documentation_order_array = explode(",", $workspace->{"documentation_ids_order"});
                                $new_documents_order = NULL;
                                $documentations_count = 0;

                                if($params["update_value"] == YES){
                                    if (($key = array_search($params["documentation_id"], $documentation_order_array)) !== false) {
                                        unset($documentation_order_array[$key]);
                                        $documentations_count = count($documentation_order_array);
                                    }

                                    $new_documents_order = ($documentations_count) ? implode(",", $documentation_order_array) : "";
                                }
                                else {
                                    $new_documents_order  = ($workspace->{"documentation_ids_order"}) ? $workspace->{"documentation_ids_order"}.','. $params["documentation_id"] : $params["documentation_id"];
                                    $documentations_count = count($this->db->query("SELECT id FROM documentations WHERE is_archived = ?", YES)->result_array());
                                }

                                $update_workspace = $this->db->query("UPDATE workspaces SET documentation_ids_order = ? WHERE id = ?", array($new_documents_order, $params["workspace_id"]));
                                
                                if($update_workspace){
                                    $response_data["result"]["message"] = ($params["update_value"] == NO) ? "You have no archived documentations yet." : "You have no documentations yet.";
                                    $response_data["result"]["documentations_count"] = $documentations_count;
                                }
                            }
                        }
                    }
                }
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        public function deleteDocumentation($documentation_id){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $delete = $this->db->query("DELETE FROM documentations WHERE id = ?;", $documentation_id);

                if($this->db->affected_rows()){
                    $response_data["status"] = true;
                }
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }
                    
    }
?>