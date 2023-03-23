<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Files extends CI_Controller {
        public function __construct(){
            parent::__construct();

            $this->load->model("File");
        }

        # DOCU: This function will call uploadFile() from File Model to process uploading of files and creating its db record
		# Triggered by: (POST) files/upload
		# Requires: $_POST["section_id", "uploaded_file[]"]
		# Returns: { status: true/false, result: { tab_id, html }, error: null }
		# Last updated at: Mar. 23, 2023
		# Owner: Jovic
        public function uploadFile(){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $response_data = $this->File->uploadFile(array(
                    "section_id" => $_POST["section_id"],
                    "files"      => $_FILES["upload_file"]
                ));
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            echo json_encode($response_data);
        }
    }
?>