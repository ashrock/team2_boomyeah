<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
	public function __construct(){
		parent::__construct();

		$this->load->model("User");
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index(){
		// Proceed to Google API if there is no session
		if(!isset($_SESSION["user_id"])){
			include_once APPPATH . "libraries/vendor/autoload.php";

			$google_client = new Google_Client();
			$google_client->setClientId("499626512701-5phs7ak08faatl5g2f9n1o4qsva5hbhq.apps.googleusercontent.com");
			$google_client->setClientSecret("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");
			$google_client->setRedirectUri("http://localhost:8888/development/");
			$google_client->addScope("profile email");

			if(isset($_GET["code"])){
				$token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

				if(!isset($token["error"])){
					$google_client->setAccessToken($token["access_token"]);
					$google_service = new Google_Service_Oauth2($google_client);
					$userinfo = $google_service->userinfo->get();

					// Create/Fetch User's account
					$register_user = $this->User->loginUser($userinfo);

					if($register_user["status"]){
						// Sample admin session
						$_SESSION["workspace_id"]  = 1;
						
						// Set user session
						$_SESSION["user_id"]       = $register_user["result"]["user_info"]["id"];
						$_SESSION["user_level_id"] = $register_user["result"]["user_info"]["user_level_id"];
						$_SESSION["first_name"]    = $register_user["result"]["user_info"]["first_name"];
						$_SESSION["last_name"]     = $register_user["result"]["user_info"]["last_name"];
						$_SESSION["email"]         = $register_user["result"]["user_info"]["email"];
	
						redirect(($register_user["result"]["user_info"]["user_level_id"] == USER_LEVEL["ADMIN"]) ? "admin_documentations" : "user_documentations");
					}
				}
			}
			else{
				// Create Auth URL for user login
				$this->load->view('users/login', array("google_login_url" => $google_client->createAuthUrl()));
			}
		}
		else{
			// Redirect User to documentations page depending on User level
			redirect(($_SESSION["user_level_id"] == USER_LEVEL["ADMIN"]) ? "admin_documentations" : "user_documentations");
		}
	}

	public function logout(){
		session_destroy();

		redirect(base_url());
	}
}
