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
                    $response_data["status"] = true;
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
    }
?>