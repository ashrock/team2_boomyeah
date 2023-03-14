<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
	public function __construct(){
		parent::__construct();

		$this->load->model("User");
	}

	# DOCU: This function will render the login page. It will also handle the data returned by Google API. It will Create/Fetch User's data and redirect them
	# to the Admin/User Documentation page
	# Triggered by: (GET) /
	# Optionals: Data returned by Google; $_SESSION["user_id", "user_level_id"]
	# Returns: login.php
	# Last updated at: Mar. 14, 2023
	# Owner: Jovic
	public function index(){
		# Proceed to Google API if there is no session
		if(!isset($_SESSION["user_id"])){
			include_once APPPATH . "libraries/vendor/autoload.php";
			
			$this->config->load("api_config");
			$google_client = new Google_Client();
			$google_client->setClientId($this->config->item("client_id"));
			$google_client->setClientSecret($this->config->item("client_secret"));
			$google_client->setRedirectUri(BASE_URL);
			$google_client->addScope("profile email");

			if(isset($_GET["code"])){
				$token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

				if(!isset($token["error"])){
					$google_client->setAccessToken($token["access_token"]);
					$google_service = new Google_Service_Oauth2($google_client);
					$userinfo = $google_service->userinfo->get();

					# Create/Fetch User's account
					$register_user = $this->User->loginUser($userinfo);

					if($register_user["status"]){
						# Sample admin session
						$_SESSION["workspace_id"]  = 1;
						
						# Set user session
						$_SESSION["user_id"]          = $register_user["result"]["user_info"]["id"];
						$_SESSION["user_level_id"]    = $register_user["result"]["user_info"]["user_level_id"];
						$_SESSION["first_name"]       = $register_user["result"]["user_info"]["first_name"];
						$_SESSION["last_name"]        = $register_user["result"]["user_info"]["last_name"];
						$_SESSION["email"]            = $register_user["result"]["user_info"]["email"];
						$_SESSION["user_profile_pic"] = $register_user["result"]["user_info"]["profile_picture"];
	
						redirect(($register_user["result"]["user_info"]["user_level_id"] == USER_LEVEL["ADMIN"]) ? "docs/edit" : "docs");
					}
				}
			}
			else{
				# Create Auth URL for user login
				$this->load->view('users/login', array("google_login_url" => $google_client->createAuthUrl()));
			}
		}
		else{
			# Redirect User to documentations page depending on User level
			redirect(($_SESSION["user_level_id"] == USER_LEVEL["ADMIN"]) ? "docs/edit" : "docs");
		}
	}

	public function logout(){
		session_destroy();

		redirect(base_url());
	}
}
?>