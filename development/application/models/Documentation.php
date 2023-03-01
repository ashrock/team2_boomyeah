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

                $get_documentations = $this->db->query("SELECT id, title, is_archived, is_private, cache_collaborators_count
                    FROM documentations
                    WHERE workspace_id = ? AND {$where_conditions}
                    ORDER BY FIELD (id, {$params["documentation_ids_order"]});", $bind_params
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