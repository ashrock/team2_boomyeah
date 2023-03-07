<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Workspaces extends CI_Controller {
		public function __construct(){
			parent::__construct();

			$this->load->model("Workspace");
		}

		# DOCU: This function will update documentation_ids_order of workspace
		# Triggered by: (POST) docs/reorder
		# Requires: $params { workspace_id, is_archived, user_level_id }
		# Optionals: $params { documentation_ids_order }, $_SESSION["workspace_id"]
		# Returns: { status: true/false, result: documentations record (Array), error: null }
		# Last updated at: March 1, 2023
		# Owner: Jovic
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
?>