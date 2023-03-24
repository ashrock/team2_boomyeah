<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class User extends CI_Model {
        # DOCU: This function will fetch User record. Create new User record if User doesn't have a record
        # Triggered by: (GET) docs
        # Requires: object returned by Google Login API
        # Returns: { status: true/false, result: user_info, error: null }
        # Last updated at: Mar. 24, 2023
        # Owner: Jovic, Updated by: Erick
        public function loginUser($userinfo){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                # Check if User exists
                $get_user = $this->db->query("SELECT id, user_level_id, first_name, last_name, email, profile_picture FROM users WHERE email = ?;", $userinfo["email"]);

                # Create User record
                if(!$get_user->num_rows()){
                    $create_user = $this->db->query("INSERT INTO users (workspace_id, user_level_id, first_name, last_name, profile_picture, email, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW());", 
                    array(VILLAGE88, USER_LEVEL["USER"], $userinfo["givenName"], $userinfo["familyName"], $userinfo["picture"], $userinfo["email"]));

                    if($create_user){
                        $user_info = array(
                            "type"            => "SIGNUP",
                            "id"              => $this->db->insert_id(),
                            "user_level_id"   => USER_LEVEL["USER"],
                            "first_name"      => $userinfo["givenName"],
                            "last_name"       => $userinfo["familyName"],
                            "profile_picture" => $userinfo["picture"],
                            "email"           => $userinfo["email"]
                        );
                    }
                }
                else {
                    # Check if First Name and Last Name exists
                    $user_info = $get_user->result_array()[FIRST_INDEX];

                    if(!$user_info["first_name"] || !$user_info["last_name"]){
                        $update_user = $this->db->query("UPDATE users SET first_name = ?, last_name = ?, profile_picture = ?, updated_at = NOW() WHERE id = ?;",
                        array($userinfo["givenName"], $userinfo["familyName"], $userinfo["picture"], $user_info["id"]));

                        if($update_user){
                            $user_info["first_name"]      = $userinfo["givenName"];
                            $user_info["last_name"]       = $userinfo["familyName"];
                            $user_info["profile_picture"] = $userinfo["picture"];
                        }
                    }
                }
                
                $response_data["status"] = true;
                $response_data["result"]["user_info"] = $user_info;
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will fetch User record
        # Triggered by: (GET) collaborators/get
        # Requires: $user_id
        # Returns: { status: true/false, result: user record, error: null }
        # Last updated at: Mar. 8, 2023
        # Owner: Jovic
        public function getUser($user_id){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $get_user = $this->db->query("SELECT id, user_level_id, first_name, last_name, email FROM users WHERE id = ?;", $user_id);

                if($get_user->num_rows()){
                    $response_data["result"] = $get_user->result_array()[FIRST_INDEX];
                }
                
                $response_data["status"] = true;
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will fetch Users record based on array of compare values
        # Triggered by: (POST) collaborators/add
        # Requires: $params { fields_to_select, compare_values }
        # Returns: { status: true/false, result: users record, error: null }
        # Last updated at: Mar. 9, 2023
        # Owner: Jovic
        public function getUsers($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $fields_to_select = isset($params["fields_to_select"]) ? $params["fields_to_select"] : "*";

                $get_user = $this->db->query("SELECT {$fields_to_select} FROM users WHERE {$params['field_to_compare']} IN ?;", array($params["compare_values"]));

                $response_data["result"] = $get_user->result_array();
                
                $response_data["status"] = true;
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will create user records
        # Triggered by: (POST) collaborators/add
        # Requires: $params { new_users_email, collaborator_emails }
        # Returns: { status: true/false, result: { ids }, error: null }
        # Last updated at: Mar. 24, 2023
        # Owner: Jovic
        public function createUsers($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $this->db->trans_start();
                # Generate values
                $values_clause = array();
                $user_level_id = USER_LEVEL["USER"];

                foreach($params["new_users_email"] as $email){
                    array_push($values_clause, "({$_SESSION['workspace_id']}, {$user_level_id}, '{$email}', NOW(), NOW())");
                }

                $values_clause = implode(", ", $values_clause);

                $create_users = $this->db->query("INSERT INTO users (workspace_id, user_level_id, email, created_at, updated_at) VALUES {$values_clause};");

                if($create_users){
                    $get_users = $this->getUsers(array(
                        "fields_to_select"  => "JSON_ARRAYAGG(id) AS user_ids",
                        "field_to_compare"  => "email",
                        "compare_values"    => $params["collaborator_emails"]
                    ));

                    if($get_users["status"]){
                        $response_data["status"]        = true;
                        $response_data["result"]["ids"] = json_decode($get_users["result"][FIRST_INDEX]["user_ids"]);

                        $this->db->trans_complete();
                    }
                }
            }
            catch (Exception $e) {
                $this->db->rollback();
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }
    }
?>