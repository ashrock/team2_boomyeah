<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Files extends CI_Controller {
        public function __construct(){
            parent::__construct();

            $this->load->model("File");
        }

        public function uploadFile(){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $response_data = $this->File->uploadFile($_FILES["uploaded_file"]);
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            echo json_encode($response_data);
        }
    }
?>