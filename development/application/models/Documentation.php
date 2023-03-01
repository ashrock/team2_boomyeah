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