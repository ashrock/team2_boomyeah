<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Tab extends CI_Model {
        # DOCU: This function will update Tab record
        # Triggered by: (POST) module/update
        # Requires: $params {action, module_title, module_content, is_comments_allowed, tab_id }, $_SESSION["user_id"]
        # Returns: { status: true/false, result: {}, error: null }
        # Last updated at: March 15, 2023
        # Owner: Jovic
        public function updateTab($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                if($params["action"] == "update_module_tab"){
                    $update_tab = $this->db->query("UPDATE tabs SET title = ?, content = ?, is_comments_allowed = ?, updated_by_user_id = ?, updated_at = NOW() WHERE id = ?;", 
                    array($params["module_title"], $params["module_content"], $params["is_comments_allowed"], $_SESSION["user_id"], $params["tab_id"]));

                    if($update_tab){
                        $response_data["status"] = true;
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