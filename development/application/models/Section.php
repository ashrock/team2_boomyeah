<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Section extends CI_Model {
        # DOCU: This function will fetch sections of a documentation
        # Triggered by: (GET) docs/:id/edit
        # Requires: $documentation_id
        # Returns: { status: true/false, result: sections records, error: null }
        # Last updated at: March 7, 2023
        # Owner: Jovic
        public function getSections($documentation_id){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                # Fetch section_ids_order
                $this->load->model("Documentation");
                $get_documentation = $this->Documentation->getDocumentation($documentation_id);

                if($get_documentation["status"] && $get_documentation["result"]){
                    # Add order by clause if section_ids_order is present
                    $order_by_clause = $get_documentation['result']['section_ids_order'] ? "ORDER BY FIELD (id, {$get_documentation['result']['section_ids_order']})" : "";

                    # Fetch sections
                    $get_sections = $this->db->query("SELECT id, title FROM sections WHERE documentation_id = ? {$order_by_clause};", $documentation_id);

                    if($get_sections->num_rows()){
                        $response_data["result"] = $get_sections->result_array();
                    }

                    $response_data["status"] = true;
                }
                else{
                    throw new Exception($get_documentation["error"]);
                }      
            }

            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }
    }
?>