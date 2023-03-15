<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Sections extends CI_Controller {
		function __construct() {
			parent::__construct();

			$this->load->model("Section");

			# Check for User session
			if(!isset($_SESSION["user_id"])){
				redirect(base_url());
			}
		}
		
		# DOCU: This function will call addSection() from Section Model to process adding of new section
		# Triggered by: (POST) sections/add
		# Requires: $_POST["documentation_id"], $_POST["section_title"]
		# Returns: { status: true/false, result: { html }, error: null }
		# Last updated at: Mar. 8, 2023
		# Owner: Erick
		public function addSection(){
			$response_data = array("status" => false, "result" => array(), "error" => null);

			try {
				# Check if user is allowed to do action
				$this->isUserAllowed();

				if(isset($_POST["section_title"])){
					$response_data = $this->Section->addSection($_POST);
				}
				else{
					$response_data["error"] = "Section title is required";
				}
			}
			catch (Exception $e) {
				$response_data["error"] = $e->getMessage();
			}

			echo json_encode($response_data);
		}
		
		# DOCU: This function will call addSection() from Section Model to process adding of new section
		# Triggered by: (POST) sections/add
		# Requires: $_POST["section_id"], $_POST["update_type"], $_POST["update_value"]
		# Returns: { status: true/false, result: { html }, error: null }
		# Last updated at: Mar. 15, 2023
		# Owner: Erick, Updated by: Jovic
		public function updateSection(){
			$response_data = array("status" => false, "result" => array(), "error" => null);

			try {
				# Check if user is allowed to do action
				$this->isUserAllowed();

				if(isset($_POST["section_id"])){
					# Process updating of documentation
					$response_data = $this->Section->updateSection($_POST);
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
		
		# DOCU: This function will call duplicateSection() from Section model
		# Triggered by: (POST) sections/duplicate
		# Requires: $_POST["section_id"]
		# Returns: { status: true/false, result: { html }, error: null }
		# Last updated at: Mar. 8, 2023
		# Owner: Erick
		public function duplicateSection(){
			$response_data = array("status" => false, "result" => array(), "error" => null);

			try {
				# Check if user is allowed to do action
				$this->isUserAllowed();

				if(isset($_POST["section_id"])){
					# Process duplicating of section
					$response_data = $this->Section->duplicateSection($_POST);
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

        # DOCU: This function will call removeSection() from Section model
		# Triggered by: (POST) sections/remove
		# Requires: $_POST["section_id"], $_POST["documentation_id"] 
		# Returns: { status: true/false, result: { html }, error: null }
		# Last updated at: Mar. 8, 2023
		# Owner: Erick
		public function removeSection(){
			$response_data = array("status" => false, "result" => array(), "error" => null);

			try {
				# Check if user is allowed to do action
				$this->isUserAllowed();

				if(isset($_POST["section_id"]) && isset($_POST["documentation_id"])){
					# Process removing of section
					$response_data = $this->Section->removeSection($_POST);
				}
				else{
					$response_data["error"] = "Section id and documentation id are required.";
				}
			}
			catch (Exception $e) {
				$response_data["error"] = $e->getMessage();
			}

			echo json_encode($response_data);
		}

        # DOCU: This function will call reOrderSection() from Section model
		# Triggered by: (POST) sections/reorder
		# Requires: $_POST["documentation_id"], $_POST["sections_order"]
		# Returns: { status: true/false, result: {}, error: null }
		# Last updated at: Mar. 9, 2023
		# Owner: Erick
		public function reOrderSection(){
			$response_data = array("status" => false, "result" => array(), "error" => null);

			try {
				# Check if user is allowed to do action
				$this->isUserAllowed();

				if(isset($_POST["documentation_id"]) && isset($_POST["sections_order"])){
					# Process reordering of section
					$response_data = $this->Section->reOrderSection($_POST);
				}
				else{
					$response_data["error"] = "Documentation id and section order are required.";
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