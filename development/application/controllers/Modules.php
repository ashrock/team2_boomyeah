<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Modules extends CI_Controller {
		function __construct() {
			parent::__construct();

			$this->load->model("Module");

			# Check for User session
			if(!isset($_SESSION["user_id"])){
				redirect(base_url());
			}
		}
		
		# DOCU: This function will call addModule() from Module Model to process adding of new module
		# Triggered by: (POST) modules/add
		# Requires: $_POST["section_id"]
		# Returns: { status: true/false, result: { html }, error: null }
		# Last updated at: Mar. 15, 2023
		# Owner: Erick
		public function addModule(){
			$response_data = array("status" => false, "result" => array(), "error" => null);

			try {
				# Check if user is allowed to do action
				$this->isUserAllowed();

				if(isset($_POST["section_id"])){
					$response_data = $this->Module->addModule($_POST);
				}
				else{
					$response_data["error"] = "Section id is required";
				}
			}
			catch (Exception $e) {
				$response_data["error"] = $e->getMessage();
			}

			echo json_encode($response_data);
		}

		# DOCU: This function will check if user is allowed to visit a page or do an action.
		# Triggered by: GET and POST functions in Documentations Controller
		# Requires: $_SESSION["user_level_id"]; $is_admin_page
		# Last updated at: Mar. 6, 2023
		# Owner: Jovic
		private function isUserAllowed($is_admin_page = true){
			if($is_admin_page && $_SESSION["user_level_id"] == USER_LEVEL["USER"]){
				redirect("/docs");
			}
		}
	}
?>