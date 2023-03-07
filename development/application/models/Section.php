<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Section extends CI_Model {
        public function getSections($documentation_id){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            $response_data["result"] = $this->db->query("SELECT * FROM sections WHERE documentation_id = ?;", $documentation_id)->result_array();

            return $response_data;
        }
    }
?>