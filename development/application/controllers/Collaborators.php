<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Collaborators extends CI_Controller {
        public function __construct(){
            parent::__construct();

            $this->load->model("Collaborator");
        }

        # DOCU: This function will call getCollaborators() from Collaborator model and return data to Admin Edit Documentation page
        # Triggered by: (GET) collaborators/get
        # Required: $_POST["document_id"]
        # Returns: { status: true/false, result: { owner, html }, error: null }
        # Last updated at: March 8, 2023
        # Owner: Jovic
        public function getCollaborators(){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                # Check if user is allowed to do action
                $this->isUserAllowed();
                
                $response_data = $this->Collaborator->getCollaborators(array("get_type" => "get_collaborators", "get_values" => array("documentation_id" => $_POST["document_id"])));
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            echo json_encode($response_data);
        }

        public function addCollaborators(){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                # Check if user is allowed to do action
                $this->isUserAllowed();
                
                $response_data = $this->Collaborator->addCollaborators($_POST);
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