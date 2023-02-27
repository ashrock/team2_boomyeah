<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Documentation extends CI_Model {
        public function getDocumentations($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $get_documentations = $this->db->query("SELECT id, title, is_archived, is_private, cache_collaborators_count
                    FROM documentations
                    WHERE workspace_id = ? AND is_archived = ?
                    ORDER BY FIELD (id, ?);", array(1, 0, $params["documentations_order"])
                );

                if($get_documentations->num_rows()){
                    $response_data["status"] = true;
                    $response_data["result"] = $get_documentations->result_array();
                }
                else{
                    throw new Exception("Error getting all documentations!");
                }
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }
    }
?>