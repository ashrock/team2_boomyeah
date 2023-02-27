<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documentations extends CI_Controller {

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
	public function admin_documentations(){
		$workspace_id = 1; // should be from session;

		$get_documentations_order = $this->Workspace->getDocumentationsOrder($workspace_id);

		$all_documentations_params = array(
			"workspace_id"         => $workspace_id,
			"documentations_order" => $get_documentations_order["result"]->documentations_order,
		);

		$all_documentations = $this->Documentation->getDocumentations($all_documentations_params);

		$this->load->view('documentations/admin_documentations', array("all_documentations" => $all_documentations["result"]));
	}
}
