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

        # DOCU: This function get the user_token to be use on auto login
        # Triggered by: (GET) /
        # Requires: $token
        # Returns: { status: true/false, result: {}, error: null }
        # Last updated at: Mar. 29, 2023
        # Owner: Erick
        public function getUserToken($token){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $get_user_by_token = $this->db->query("
                    SELECT users.* FROM user_tokens
                    INNER JOIN users ON users.id = user_tokens.user_id
                    WHERE token = ? LIMIT 1
                ", $token);

                if($get_user_by_token->num_rows()){
                    $response_data["status"] = true;
                    $user_details = $get_user_by_token->result_array()[FIRST_INDEX];

                    $_SESSION["workspace_id"]     = VILLAGE88;
                    $_SESSION["user_id"]          = $user_details["id"];
                    $_SESSION["user_level_id"]    = $user_details["user_level_id"];
                    $_SESSION["first_name"]       = $user_details["first_name"];
                    $_SESSION["last_name"]        = $user_details["last_name"];
                    $_SESSION["email"]            = $user_details["email"];
                    $_SESSION["user_profile_pic"] = $user_details["profile_picture"];
                    
                }
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function create data in user_tokens for auto login
        # Triggered by: (GET) /
        # Requires: $user_id
        # Returns: { status: true/false, result: {}, error: null }
        # Last updated at: Mar. 29, 2023
        # Owner: Erick
        public function createUserToken($user_id){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $delete_record = $this->db->query("DELETE FROM user_tokens WHERE user_id = ?", $_SESSION["user_id"]);

                $ciphering_value = $this->config->item("ciphering_value");   
                $iv_length = openssl_cipher_iv_length($ciphering_value);  
                $encryption_iv_value = random_bytes($iv_length);  

                # Encrypt the token to be save
                $random_token = bin2hex(random_bytes(16));
                $encrypted_token = openssl_encrypt($random_token.$user_id, $ciphering_value, $this->config->item("encryption_key"), ZERO_VALUE, $encryption_iv_value);    
                
                # Cookie will expire 1 month after
                $expired_at = time() + 60 * 60 * 24 * 30;
                setcookie('remember_me', $encrypted_token, $expired_at, '', '', '', true);

                $create_user_token = $this->db->query("INSERT INTO user_tokens (user_id, token, created_at, updated_at) VALUES (?, ?, NOW(), NOW())", array($_SESSION["user_id"], $encrypted_token));

                if($create_user_token){
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