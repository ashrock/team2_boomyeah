<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Workspace extends CI_Model {
        public function getDocumentationsOrder($workspace_id){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $get_documentations_order = $this->db->query("SELECT documentation_ids_order FROM workspaces WHERE id = ?;", $workspace_id);

                if($get_documentations_order->num_rows()){
                    $response_data["status"] = true;
                    $response_data["result"] = $get_documentations_order->result_array()[0];
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