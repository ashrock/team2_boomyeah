<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class User extends CI_Model {
        # DOCU: This function will fetch User record. Create new User record if User doesn't have a record
        # Triggered by: (GET) docs
        # Requires: object returned by Google Login API
        # Returns: { status: true/false, result: user_info, error: null }
        # Last updated at: Feb. 28, 2023
        # Owner: Jovic
        public function loginUser($userinfo){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                # Check if User exists
                $get_user = $this->db->query("SELECT id, user_level_id, first_name, last_name, email FROM users WHERE email = ?;", $userinfo["email"]);

                # Create User record
                if(!$get_user->num_rows()){
                    $create_user = $this->db->query("INSERT INTO users (workspace_id, user_level_id, first_name, last_name, email, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW());", 
                    array(1, USER_LEVEL["USER"], $userinfo["givenName"], $userinfo["familyName"], $userinfo["email"]));

                    $user_info = array(
                        "type"          => "SIGNUP",
                        "id"            => $this->db->insert_id(),
                        "user_level_id" => USER_LEVEL["USER"],
                        "first_name"    => $userinfo["givenName"],
                        "last_name"     => $userinfo["familyName"],
                        "email"         => $userinfo["email"]
                    );
                }
                else {
                    # Return User record
                    $user_info = $get_user->result_array()[0];
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
        # Triggered by: (GET) docs/get_collaborators
        # Requires: $user_id
        # Returns: { status: true/false, result: user record, error: null }
        # Last updated at: Mar. 8, 2023
        # Owner: Jovic
        public function getUser($user_id){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $get_user = $this->db->query("SELECT id, user_level_id, first_name, last_name, email FROM users WHERE id = ?;", $user_id);

                if($get_user->num_rows()){
                    $response_data["result"] = $get_user->result_array()[0];
                }
                
                $response_data["status"] = true;
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }
    }
?>