<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Collaborator extends CI_Model {
        public function deleteCollaborators($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $where_clause = "";
                $bind_params = (count($params) > 1) ? array() : null;
                
                foreach($params as $key => $value){
                    $where_clause .= " {$key} = ?";
                    
                    if($key != array_key_last($params)){
                        $where_clause .= " AND";
                    }
                    
                    if(count($params) > 1){
                        array_push($bind_params, $value);
                    }
                    else {
                        $bind_params = $value;
                    }
                }

                $delete_collaborators = $this->db->query("DELETE FROM collaborators WHERE {$where_clause};", $bind_params);

                $response_data["status"] = true;
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }
    }
?>