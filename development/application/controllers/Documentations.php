<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documentations extends CI_Controller {
	function __construct() {
        parent::__construct();

		$this->load->model("Workspace");
		$this->load->model("Documentation");

		// Check for User session
		if(!isset($_SESSION["user_id"])){
			redirect(base_url());
		}
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
	public function adminDocumentations(){
		if($_SESSION["user_level_id"] == USER_LEVEL["ADMIN"]){
			$all_documentations = $this->Documentation->getDocumentations($this->setGetDocumentationsParams());
	
			$this->load->view('documentations/admin_documentations', array("all_documentations" => $all_documentations["result"]));
		}
		else {
			echo "SAMPLE 404 PAGE";
		}
	}

	public function userDocumentations(){
		if($_SESSION["user_level_id"] == USER_LEVEL["USER"]){
			$all_documentations = $this->Documentation->getDocumentations($this->setGetDocumentationsParams());

			$this->load->view('documentations/user_documentations', array("all_documentations" => $all_documentations["result"]));
		}
		else {
			echo "SAMPLE 404 PAGE";
		}
	}

	public function getDocumentations(){
		$response_data = array("status" => true, "result" => array(), "error" => null);

		try {
			// Finalize params
			$all_documentations = $this->Documentation->getDocumentations($this->setGetDocumentationsParams($_POST));

			if($all_documentations["status"]){
				if(count($all_documentations["result"])){
					$response_data["result"]["html"] = "";

					for($documentations_index = 0; $documentations_index < count($all_documentations["result"]); $documentations_index++){
						$response_data["result"]["html"] .= $this->load->view("partials/document_block_partial.php", $all_documentations["result"][$documentations_index], true);
					}
				}
				else{
					$response_data["result"]["html"] = $this->load->view(
						'partials/no_documentations_partial.php', 
						array('message' => ($_POST["is_archived"] == FALSE_VALUE) ? "You have no documentations yet." : "You have no archived documentations yet."),
						true
					);
				}

				$response_data["status"] = true;
				$response_data["result"]["is_archived"] = $_POST["is_archived"];
			}
			else {
				throw new Exception($all_documentations["error"]);
			}

		}
		catch (Exception $e) {
			$response_data["error"] = $e->getMessage();
		}

		echo json_encode($response_data);
	}

	public function addDocumentations(){
		$response_data = array("status" => false, "result" => array(), "error" => null);

		try {
			if(isset($_POST["document_title"])){
				$response_data = $this->Documentation->addDocumentations(array(
					"user_id"      => $_SESSION["user_id"],
					"workspace_id" => $_SESSION["workspace_id"],
					"title"		   => $_POST["document_title"]
				));
			}
			else{
				$response_data["error"] = "Document title is required";
			}
		}
		catch (Exception $e) {
			$response_data["error"] = $e->getMessage();
		}

		echo json_encode($response_data);
	}

	public function updateDocumentations(){
		$response_data = array("status" => false, "result" => array(), "error" => null);

		try {
			if(isset($_POST["update_type"]) && isset($_POST["documentation_id"])){

				$response_data = $this->Documentation->updateDocumentations(array(
					"user_id"     	   => $_SESSION["user_id"],
					"workspace_id" 	   => $_SESSION["workspace_id"],
					"documentation_id" => $_POST["documentation_id"],
					"update_type" 	   => $_POST["update_type"],
					"update_value"     => $_POST["update_value"]
				));
			}
			else{
				$response_data["error"] = "Document id and update_type are required";
			}
		}
		catch (Exception $e) {
			$response_data["error"] = $e->getMessage();
		}

		echo json_encode($response_data);
	}

	// Private functions
	private function setGetDocumentationsParams($params = null){
		$get_documentations_order = $this->Workspace->getDocumentationsOrder($_SESSION["workspace_id"]);

		$get_documentations_params = array(
			"user_id"                 => $_SESSION["user_id"],
			"workspace_id"            => $_SESSION["workspace_id"],
			"user_level_id"           => $_SESSION["user_level_id"],
			"is_archived"             => isset($params["is_archived"]) ? $params["is_archived"] : FALSE_VALUE,
			"documentation_ids_order" => $get_documentations_order["result"]["documentation_ids_order"],
		);

		return $get_documentations_params;
	}
}
