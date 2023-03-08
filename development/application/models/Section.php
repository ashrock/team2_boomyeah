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
        
        # DOCU: This function will add section to a documentation
        # Triggered by: (POST) sections/add
        # Requires: $params { documentation_id, user_id, title }
        # Returns: { status: true/false, result: { html }, error: null }
        # Last updated at: March 8, 2023
        # Owner: Erick
        public function addSection($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                # Fetch section_ids_order
                $this->load->model("Documentation");
                $get_documentation = $this->Documentation->getDocumentation($params["documentation_id"]);

                if($get_documentation["status"] && $get_documentation["result"]){
                    $insert_section_record = $this->db->query("
                        INSERT INTO sections (documentation_id, user_id,  title, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())",
                        array($params["documentation_id"], $params["user_id"], $params["title"])
                    );

                    $new_section_id = $this->db->insert_id($insert_section_record);

                    # Check if the new section is successfully added
                    if($new_section_id > ZERO_VALUE){
                        # Append the new section id in documentation section_ids_order
                        $section_order = $get_documentation["result"]["section_ids_order"];
                        $new_section_order  = ($section_order) ? $section_order.','.$new_section_id : $new_section_id;
                        
                        if($new_section_order){
                            # Update the new section_ids_order in documentations table
                            $update_documentation = $this->db->query("
                                UPDATE documentations SET section_ids_order = ?, updated_at=NOW(), updated_by_user_id =? WHERE id = ?", 
                                array($new_section_order, $params["user_id"], $params["documentation_id"])
                            );

                            # Check if section_ids_order is successfully updated
                            if($update_documentation){
                                $new_section = $this->db->query("SELECT id, title FROM sections WHERE id = ?", $new_section_id)->result_array();

                                if($new_section){
                                    $response_data["status"] = true;
                                    $response_data["result"]["html"] = $this->load->view('partials/section_block_partial.php', array("all_sections" => $new_section), true);
                                }
                            }
                        }
                    }
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