<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class File extends CI_Model {
        # DOCU: This function will update documentations_ids_order of current Workspace
        # Triggered by: (POST) docs/remove
        # Requires: $params {"documentation_ids_order", "workspace_id"}
        # Returns: { status: true/false, result: array(), error: null }
        # Last updated at: March 1, 2023
        # Owner: Jovic
        public function uploadFile($file_params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $response_data["result"]["file"] = $file_params;
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }
    }
?>