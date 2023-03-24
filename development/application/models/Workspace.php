<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Workspace extends CI_Model {
        # DOCU: This function will fetch documentations_ids_order of current Workspace
        # Triggered by: (GET) docs/edit, docs; (POST) docs/get, docs/add, docs/update, docs/duplicate, docs/remove
        # Requires: $workspace_id
        # Returns: { status: true/false, result: documentations_ids_order (String), error: null }
        # Last updated at: Mar. 24, 2023
        # Owner: Jovic
        public function getDocumentationsOrder($workspace_id){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $get_documentations_order = $this->db->query("SELECT documentation_ids_order FROM workspaces WHERE id = ?;", $workspace_id);

                if($get_documentations_order->num_rows()){
                    $response_data["status"] = true;
                    $response_data["result"] = $get_documentations_order->result_array()[FIRST_INDEX];
                }
                else{
                    throw new Exception("Error getting documentations ids order!");
                }
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will update documentations_ids_order of current Workspace
        # Triggered by: (POST) docs/remove
        # Requires: $params {"documentation_ids_order", "workspace_id"}
        # Returns: { status: true/false, result: array(), error: null }
        # Last updated at: March 1, 2023
        # Owner: Jovic
        public function updateDocumentationsIdsOrder($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $update_workspace = $this->db->query("UPDATE workspaces SET documentation_ids_order = ? WHERE id = ?;", $params);

                if($update_workspace){
                    $response_data["status"] = true;
                }
                else{
                    throw new Exception("Error updating documentation ids order!");
                }
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }
    }
?>