<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Collaborator extends CI_Model {
        # DOCU: This function will fetch a collaborator based on user_id and documentation_id
        # Triggered by: POST and GET requests where getDocumentation is used
        # Requires: $params (user_id, documentation_id)
        # Returns: { status: true/false, result: () , error: "User has no access to this Documentation." }
        # Last updated at: March 15, 2023
        # Owner: Jovic
        public function getCollaborator($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try{
                $get_collaborator = $this->db->query("SELECT id FROM collaborators WHERE user_id = ? AND documentation_id = ?", $params);

                if(!$get_collaborator->num_rows()){
                    throw new Exception("User has no access to this Documentation.");
                }

                $response_data["status"] = true;
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will fetch the owner and collaborators of a documentation
        # Triggered by: (GET) collaborators/get
        # Requires: $documentation_id
        # Returns: { status: true/false, result: { owner, html }, error: null }
        # Last updated at: March 30, 2023
        # Owner: Jovic
        public function getCollaborators($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $this->load->model("Documentation");

                $get_owner = $this->Documentation->getDocumentationOwner($params["get_values"]["documentation_id"]);

                if(!$get_owner["result"]){
                    throw new Exception("Failed to fetch owner of Documentation");
                }
                
                if($params["get_type"] == "get_collaborators"){
                    $where_conditions = "collaborators.documentation_id = ?";
                    $bind_params      = $params["get_values"]["documentation_id"];

                    $response_data["result"]["owner"] = $this->load->view("partials/owner_user_partial.php", $get_owner["result"], true);
                }
                else{
                    $where_conditions = "collaborators.documentation_id = ? AND users.email IN ?";
                    $bind_params      = array($params["get_values"]["documentation_id"], $params["get_values"]["collaborator_emails"]);

                    $response_data["result"]["owner"] = $get_owner["result"];
                }

                $get_collaborators = $this->db->query("
                    SELECT
                        users.id, users.email, CONCAT(users.first_name, ' ', users.last_name) AS name, users.profile_picture,
                        collaborators.documentation_id, collaborators.id AS collaborator_id, collaborators.collaborator_level_id
                    FROM users
                    INNER JOIN collaborators ON collaborators.user_id = users.id
                    WHERE {$where_conditions};", $bind_params
                );

                if($params["get_type"] == "get_collaborators"){
                    $response_data["result"]["html"] = $this->load->view("partials/invited_user_partial.php", array("collaborators" => $get_collaborators->result_array()), true);
                }
                else{
                    $response_data["result"]["existing_emails"] = $get_collaborators->result_array();
                }

                $response_data["status"] = true;
                
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will create collaborator records
        # Triggered by: (POST) collaborators/add
        # Requires: $params { document_id, collaborator_emails }
        # Returns: { status: true/false, result: { html }, error: null }
        # Last updated at: April 10, 2023
        # Owner: Jovic
        public function addCollaborators($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $this->db->trans_start();

                # Check if user is an admin
                if($_SESSION["user_level_id"] == USER_LEVEL["ADMIN"]){
                    $collaborator_emails = explode(",", $params["collaborator_emails"]);
    
                    # Check if admin is inviting themself
                    if(in_array($_SESSION["email"], $collaborator_emails)){
                        $response_data["error"] = "You can't invite yourself as collaborator.";
                    }
                    
                    # Get documentation collaborators
                    $get_collaborators = $this->getCollaborators(array(
                        "get_type"   => "check_collaborators", 
                        "get_values" => array("documentation_id" => $params["document_id"], "collaborator_emails" => $collaborator_emails)
                    ));
    
                    if($get_collaborators["status"]){
                        if($get_collaborators["result"]){
                            # Check if owner is being invited
                            if(in_array($get_collaborators["result"]["owner"]["email"], $collaborator_emails)){
                                $response_data["error"] = "You can't invite the owner as collaborator.";
                                $response_data["result"]["email"] = $get_collaborators["result"]["owner"]["email"];
                            }
    
                            # Check for existing collaborators
                            if($get_collaborators["result"]["existing_emails"]){
                                $response_data["error"] = "You can't invite an existing collaborator" ;
                                $response_data["result"]["email"] = $get_collaborators["result"]["existing_emails"];
                            }
                        }
    
                        # Proceed if there's no error
                        if(!$response_data["error"]){
                            # Check if email belongs to existing user
                            $this->load->model("User");
                            $get_users = $this->User->getUsers(array(
                                "fields_to_select"  => "JSON_ARRAYAGG(email) AS user_emails",
                                "field_to_compare"  => "email",
                                "compare_values"    => $collaborator_emails
                            ));
    
                            $existing_emails = json_decode($get_users["result"][FIRST_INDEX]["user_emails"], false);
    
                            # Remove existing users from collaborator_emails
                            $new_users = ($existing_emails) ? array_diff($collaborator_emails, $existing_emails) : $collaborator_emails;
    
                            # Prepare values for creating collaborator records
                            $values_clause = array();
                            $collaborator_level_id = COLLABORATOR_LEVEL["VIEWER"];
    
                            # Create user record for emails that doesn't belong to existing users
                            if($new_users){
                                $create_users = $this->User->createUsers(array("new_users_email" => $new_users, "collaborator_emails" => $collaborator_emails));
    
                                if($create_users["status"]){
                                    # Collect ids of new and existing users
                                    $user_ids = $create_users["result"]["ids"];
                                }
                            }
                            else{
                                $get_users = $this->User->getUsers(array(
                                    "fields_to_select"  => "JSON_ARRAYAGG(id) AS user_ids",
                                    "field_to_compare"  => "email",
                                    "compare_values"    => $collaborator_emails
                                ));
    
                                if($get_users["status"]){
                                    # Collect ids of new and existing users
                                    $user_ids = json_decode($get_users["result"][FIRST_INDEX]["user_ids"]);
                                }
                            }
    
                            # Generate values for creating collaborator records
                            foreach($user_ids as $user_id){
                                array_push($values_clause, "({$user_id}, {$_SESSION['workspace_id']}, {$params["document_id"]}, {$collaborator_level_id}, NOW(), NOW())");
                            }
    
                            $values_clause = implode(", ", $values_clause);
                            $create_collaborators = $this->db->query("INSERT INTO collaborators (user_id, workspace_id, documentation_id, collaborator_level_id, created_at, updated_at) VALUES {$values_clause};");
    
                            if($create_collaborators){
                                # Get created collaborators data to be used in generating html
                                $get_collaborators = $this->db->query("
                                    SELECT
                                        users.id, users.email,
                                        collaborators.documentation_id, collaborators.id AS collaborator_id, collaborators.collaborator_level_id
                                    FROM users
                                    INNER JOIN collaborators ON collaborators.user_id = users.id
                                    WHERE collaborators.documentation_id = ? AND users.id IN ?;
                                ", array($params["document_id"], $user_ids));
    
                                if($get_collaborators){
                                    # Update cache_collaborators_count
                                    $this->load->model("Documentation");
                                    $update_documentation = $this->Documentation->updateDocumentations(array(
                                        "documentation_id"   => $params["document_id"],
                                        "update_type" 	     => "cache_collaborators_count",
                                        "update_value"       => "add_collaborator",
                                        "collaborator_count" => $get_collaborators->num_rows()
                                    ));
    
                                    if($update_documentation["status"]){
                                        $response_data["status"]         = true;
                                        $response_data["result"]["html"] = $this->load->view("partials/invited_user_partial.php", array("collaborators" => $get_collaborators->result_array()), true);
                                        $response_data["result"]["cache_collaborators_count"] = $update_documentation["result"]["cache_collaborators_count"];
                                        $response_data["result"]["get_collab"] = $get_collaborators->result_array();
        
                                        $this->db->trans_complete();
                                    }
                                }
                            }
                        }
                    }
                }
            }
            catch (Exception $e) {
			    $this->db->trans_rollback();
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will update collaborator record
        # Triggered by: (POST) collaborators/update
        # Requires: $params { invited_user_id, collaborator_id, update_type, update_value, email }
        # Returns: { status: true/false, result: { collaborator_level_id }, error: null }
        # Last updated at: April 10, 2023
        # Owner: Jovic
        public function updateCollaborator($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try{
			    $this->db->trans_start();

                # Check if user is an admin
                if($_SESSION["user_level_id"] == USER_LEVEL["ADMIN"]){
                    $update_collaborator = $this->db->query("UPDATE collaborators SET collaborator_level_id = ?, updated_at = NOW() WHERE id = ?;", array($params["update_value"], $params["collaborator_id"]));

                    if($update_collaborator){
                        $response_data["status"] = true;
                        $response_data["result"]["invited_user_id"]       = $params["invited_user_id"];
                        $response_data["result"]["collaborator_level_id"] = $params["update_value"];

                        $this->db->trans_complete();
                    }
                }
            }
            catch (Exception $e) {
			    $this->db->trans_rollback();
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will remove collaborator record
        # Triggered by: (POST) collaborators/remove
        # Requires: $params { invited_user_id, collaborator_id }
        # Returns: { status: true/false, result: { invited_user_id }, error: null }
        # Last updated at: April 10, 2023
        # Owner: Jovic  
        public function removeCollaborator($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try{
			    $this->db->trans_start();

                # Check if user is an admin
                if($_SESSION["user_level_id"] == USER_LEVEL["ADMIN"]){
                    $remove_collaborator = $this->db->query("DELETE FROM collaborators WHERE id = ?;", $params["collaborator_id"]);
    
                    if($remove_collaborator){
                        $this->load->model("Documentation");
                        $update_documentation = $this->Documentation->updateDocumentations(array(
                            "documentation_id" => $params["documentation_id"],
                            "update_type" 	   => "cache_collaborators_count",
                            "update_value"     => "remove_collaborator"
                        ));
    
                        if($update_documentation["status"]){
                            $response_data["status"]                              = true;
                            $response_data["result"]["invited_user_id"]           = $params["invited_user_id"];
                            $response_data["result"]["documentation_id"]          = $params["documentation_id"];
                            $response_data["result"]["cache_collaborators_count"] = $update_documentation["result"]["cache_collaborators_count"];
        
                            $this->db->trans_complete();
                        }
                    }
                }
            }
            catch (Exception $e) {
			    $this->db->trans_rollback();
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will delete collaborators based on documentation_id
        # Triggered by: (POST) docs/remove
        # Requires: $params (e.g. id, documentation_id, etc.)
        # Returns: { status: true/false, result: array(), error: null }
        # Last updated at: Aroil 10, 2023
        # Owner: Jovic
        public function removeCollaborators($documentation_id){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                # Check if user is an admin
                if($_SESSION["user_level_id"] == USER_LEVEL["ADMIN"]){
                    $remove_collaborators = $this->db->query("DELETE FROM collaborators WHERE documentation_id = ?;", $documentation_id);
    
                    if($remove_collaborators){
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