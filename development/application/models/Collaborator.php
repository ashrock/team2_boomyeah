<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Collaborator extends CI_Model {
        # DOCU: This function will fetch the owner and collaborators of a documentation
        # Triggered by: (GET) docs/get_collaborators
        # Requires: $documentation_id
        # Returns: { status: true/false, result: { owner, html }, error: null }
        # Last updated at: March 8, 2023
        # Owner: Jovic
        public function getCollaborators($documentation_id){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $this->load->model("Documentation");
                $get_owner = $this->Documentation->getDocumentationOwner($documentation_id);

                if($get_owner["result"]){
                    $response_data["result"]["owner"] = $this->load->view("partials/owner_user_partial.php", $get_owner["result"], true);
                    
                    $get_collaborators = $this->db->query("
                        SELECT
                            users.id, users.email,
                            collaborators.id AS collaborator_id, collaborators.collaborator_level_id
                        FROM users
                        INNER JOIN collaborators ON collaborators.user_id = users.id
                        WHERE collaborators.documentation_id = ?;", $documentation_id
                    );
    
                    $response_data["result"]["html"] = $this->load->view("partials/invited_user_partial.php", array("collaborators" => $get_collaborators->result_array()), true);
    
                    $response_data["status"] = true;
                }
                else{
                    throw new Exception("Failed to fetch owner of Documentation");
                }
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

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