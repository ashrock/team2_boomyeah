<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Workspaces extends CI_Controller {
	public function __construct(){
		parent::__construct();

		$this->load->model("Workspace");
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
    public function updateWorkspace(){
        $response_data = array("status" => false, "result" => array(), "error" => null);

		try {
			if(isset($_POST["documentations_order"])){
				$response_data = $this->Workspace->updateDocumentationsIdsOrder(array("documentations_order" => $_POST["documentations_order"], "workspace_id" => $_SESSION["workspace_id"]));
			}
		}
		catch (Exception $e) {
			$response_data["error"] = $e->getMessage();
		}

		echo json_encode($response_data);
    }
}
