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

	// Private functions
	private function setGetDocumentationsParams(){
		$get_documentations_order = $this->Workspace->getDocumentationsOrder($_SESSION["workspace_id"]);

		$get_documentations_params = array(
			"user_id"                 => $_SESSION["user_id"],
			"workspace_id"            => $_SESSION["workspace_id"],
			"user_level_id"           => $_SESSION["user_level_id"],
			"documentation_ids_order" => $get_documentations_order["result"]["documentation_ids_order"],
		);

		return $get_documentations_params;
	}
}
