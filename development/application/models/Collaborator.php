<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Collaborator extends CI_Model {
        # DOCU: This function will delete collaborators depending on $params given.
        # Triggered by: (POST) docs/remove
        # Requires: $params (e.g. id, documentation_id, etc.)
        # Returns: { status: true/false, result: array(), error: null }
        # Last updated at: March 6, 2023
        # Owner: Jovic
        public function deleteCollaborators($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $where_clause = [];
                # Set $bind_params to an array if there are multiple params value
                $bind_params = (count($params) > 1) ? array() : null;
                
                # Create where clause based on params given. 
                foreach($params as $key => $value){
                    array_push($where_clause, "{$key} = ?");
                    
                    if(count($params) > 1){
                        array_push($bind_params, $value);
                    }
                    else {
                        $bind_params = $value;
                    }
                }

                # Add 'AND' for each $where_clause value
                $where_clause = implode("AND ", $where_clause);

                $delete_collaborators = $this->db->query("DELETE FROM collaborators WHERE {$where_clause};", $bind_params);

                if($delete_collaborators){
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