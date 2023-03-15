<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Section extends CI_Model {
        # DOCU: This function will fetch sections of a documentation
        # Triggered by: (GET) docs/:id/edit
        # Requires: $documentation_id
        # Returns: { status: true/false, result: sections records, error: null }
        # Last updated at: March 10, 2023
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
                    $get_sections = $this->db->query("SELECT id, title, description FROM sections WHERE documentation_id = ? {$order_by_clause};", $documentation_id);

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
        
        # DOCU: This function will fetch the section data
        # Triggered by: Any models that needs to fetch data of a section
        # Requires: $section_id
        # Returns: { status: true/false, result: { section_data }, error: null }
        # Last updated at: March 8, 2023
        # Owner: Erick
        public function getSection($section_id){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $get_section = $this->db->query("SELECT id, documentation_id, title, description FROM sections WHERE id = ?", $section_id);
                
                if($get_section->num_rows()){                    
                    $response_data["status"] = true;
                    $response_data["result"] = $get_section->result_array()[0];
                }
                else{
                    throw new Exception("No section found.");
                }      
            }

            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will fetch tabs of a section
        # Triggered by: (GET) docs/:documentation_id/:section_id/edit
        # Requires: $section_id
        # Returns: { status: true/false, result: { section_tabs data }, error: null }
        # Last updated at: March 15, 2023
        # Owner: Jovic
        public function getSectionTabs($section_id){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $get_section = $this->db->query("
                    SELECT
                        modules.id AS module_id, 
                        modules.tab_ids_order,
                        ANY_VALUE(module_tabs.tabs) AS module_tabs_json
                    FROM sections
                    INNER JOIN modules ON modules.section_id = sections.id
                    LEFT JOIN (
                            SELECT
                                tabs.module_id,
                                JSON_OBJECTAGG(
                                    tabs.id,
                                    JSON_OBJECT(
                                        'id', tabs.id,
                                        'module_id', tabs.module_id,
                                        'title', tabs.title,
                                        'content', tabs.content,
                                        'cache_posts_count', tabs.cache_posts_count,
                                        'is_comments_allowed', tabs.is_comments_allowed
                                    )
                                ) AS tabs
                            FROM tabs
                        GROUP BY module_id
                    ) AS module_tabs ON module_tabs.module_id = modules.id
                    WHERE sections.id = ?
                    GROUP BY modules.id;
                ", $section_id);
                
                if($get_section->num_rows()){                    
                    $response_data["status"] = true;
                    $response_data["result"] = $get_section->result_array();
                }
                else{
                    throw new Exception("No section found.");
                }      
            }

            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will add section to a documentation
        # Triggered by: (POST) sections/add
        # Requires: $params { documentation_id, user_id, section_title }
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
                        INSERT INTO sections (documentation_id, user_id, title, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())",
                        array($params["documentation_id"], $_SESSION["user_id"], $params["section_title"])
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
                                array($new_section_order, $_SESSION["user_id"], $params["documentation_id"])
                            );

                            # Check if section_ids_order is successfully updated
                            if($update_documentation){
                                $new_section = $this->getSection($new_section_id);

                                if($new_section["status"]){
                                    $response_data["status"]               = true;
                                    $response_data["result"]["section_id"] = $new_section_id;
                                    $response_data["result"]["html"]       = $this->load->view('partials/section_block_partial.php', array("all_sections" => array($new_section["result"])), true);
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

        # DOCU: This function will duplicate section records based on documentation_id
        # Triggered by: (POST) docs/duplicate
        # Requires: $params { duplicate_id, documentation_id, section_ids_order }
        # Returns: { status: true/false, result: {}, error: null }
        # Last updated at: March 10, 2023
        # Owner: Jovic
        public function duplicateSections($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                # Create sections
                $create_sections = $this->db->query("
                    INSERT INTO sections (documentation_id, user_id, title, description, created_at, updated_at)
                    SELECT ?, ?, title, description, NOW(), NOW() FROM sections WHERE documentation_id = ? ORDER BY FIELD(id, {$params['section_ids_order']});",
                    array($params["duplicate_id"], $_SESSION["user_id"], $params["documentation_id"])
                );

                if($create_sections){
                    $response_data["status"] = true;
                }
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will update a section depending on what update_type is given
        # Triggered by: (POST) docs/update
        # Requires: $params { update_type, update_value, section_id }
        # Returns: { status: true/false, result: {}, error: null }
        # Last updated at: March 8, 2023
        # Owner: Erick
        public function updateDocumentation($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $section = $this->getSection($params["section_id"]);
                
                # Check section if existing
                if($section["status"]){
                    # Double check if update_type only have this following values: "title", "description"
                    if( in_array($params["update_type"], ["title", "description"]) ){
                        $update_section = $this->db->query("
                            UPDATE sections SET {$params["update_type"]} = ?, updated_by_user_id = ?, updated_at = NOW() WHERE id = ?", 
                            array($params["update_value"], $_SESSION["user_id"], $params["section_id"]) 
                        );
                        
                        $response_data["status"] = ($update_section);
                    }
                }
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will duplicate a section
        # Triggered by: (POST) sections/duplicate
        # Requires: $params { section_id }
        # Returns: { status: true/false, result: {}, error: null }
        # Last updated at: March 8, 2023
        # Owner: Erick
        public function duplicateSection($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $section = $this->getSection($params["section_id"]);
                
                # Check document id if existing
                if($section["status"]){
                    $section_data = $section["result"];

                    $insert_duplicate_section_record = $this->db->query("
                        INSERT INTO sections (documentation_id, user_id, title, description, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())",
                        array($section_data["documentation_id"], $_SESSION["user_id"], "Copy of {$section_data['title']}", $section_data["description"])
                    );

                    $new_section_id = $this->db->insert_id($insert_duplicate_section_record);

                    if($new_section_id > ZERO_VALUE){
                        $this->load->model("Documentation");
                        $documentation = $this->Documentation->getDocumentation($section_data["documentation_id"]);

                        if($documentation["status"]){
                            $new_sections_order = explode(",", $documentation["result"]["section_ids_order"]);
                            
                            # Manipulate current order of section_ids_order when duplicating a section
                            for($document_index=0; $document_index < count($new_sections_order); $document_index++){
                                if($section_data["id"] == (int)$new_sections_order[$document_index]){
                                    array_splice($new_sections_order, $document_index + 1, 0, "{$new_section_id}");
                                }
                            }
            
                            # Convert array to comma-separated string and update section_ids_order of documentations
                            $new_sections_order = implode(",", $new_sections_order);

                            # Update documentations section_ids_order
                            $update_docs_section_order = $this->db->query("UPDATE documentations SET section_ids_order = ? WHERE id = ?", array($new_sections_order, $section_data["documentation_id"]));

                            if($update_docs_section_order){
                                $new_section = $this->getSection($new_section_id);

                                if($new_section["status"]){
                                    $response_data["status"] = true;
                                    $response_data["result"]["section_id"] = $new_section_id;
                                    $response_data["result"]["html"] = $this->load->view('partials/section_block_partial.php', array("all_sections" => array($new_section["result"])), true);
                                }

                                # TODO: For v.03, implement duplicating of modules using section_id and tabs using module_id.
                            }
                        }
                    }
                }
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will delete a section
        # Triggered by: (POST) docs/remove
        # Requires: $params { documentation_id, section_id }
        # Returns: { status: true/false, result: {}, error: null }
        # Last updated at: March 9, 2023
        # Owner: Erick
        public function removeSection($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                # Start DB transaction
                $this->db->trans_start();

                $section = $this->getSection($params["section_id"]);
               
                # Check section if existing
                if($section["status"]){
                    # TODO: For v.03, remove modules, tabs that is associated on sections table.

                    $delete_section = $this->db->query("DELETE FROM sections WHERE id = ?;", $params["section_id"]);

                    if($delete_section){
                        # Fetch section_ids_order
                        $this->load->model("Documentation");
                        $documentation = $this->Documentation->getDocumentation($params["documentation_id"]);
    
                        if($documentation["status"]){
                            # Remove section_id from section_ids_order and update documentation record
                            $sections_order = explode(",", $documentation["result"]["section_ids_order"]);
                            $section_index  = array_search($params["section_id"], $sections_order);
                            
                            if($section_index !== FALSE){
                                unset($sections_order[$section_index]);
                                $sections_count = count($sections_order);
                                $sections_order = ($sections_count) ? implode(",", $sections_order) : "";

                                # Update documentations section_ids_order
                                $update_docs_section_order = $this->db->query("UPDATE documentations SET section_ids_order = ? WHERE id = ?", array($sections_order, $params["documentation_id"]));
                                
                                if($update_docs_section_order){
                                    # Commit changes to DB
                                    $this->db->trans_complete();

                                    $response_data["status"] = true;
                                    $response_data["result"]["section_id"] = $section["result"]["id"];
                                }
                            }
                            else{
                                $this->db->trans_rollback();
                                throw new Exception("Unable to delete section, the section is not included in the section_ids_order field.");
                            }
                        }
                        else{
                            throw new Exception($documentation["error"]);
                        }
                    }
                }
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will all section records with a matching documentation_id
        # Triggered by: (POST) docs/remove
        # Requires: $documentation_id
        # Returns: { status: true/false, result: {}, error: null }
        # Last updated at: March 10, 2023
        # Owner: Jovic
        public function removeSections($documentation_id){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $remove_sections = $this->db->query("DELETE FROM sections WHERE documentation_id = ?;", $documentation_id);

                if($remove_sections){
                    $response_data["status"] = true;
                }

            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will reorder the sections of a documentation
        # Triggered by: (POST) sections/reorder
        # Requires: $params { documentation_id, sections_order }
        # Returns: { status: true/false, result: {}, error: null }
        # Last updated at: March 9, 2023
        # Owner: Erick
        public function reOrderSection($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                # Fetch section_ids_order
                $this->load->model("Documentation");
                $documentation = $this->Documentation->getDocumentation($params["documentation_id"]);

                if($documentation["status"]){
                    # Update documentations section_ids_order
                    $update_docs_section_order = $this->db->query("UPDATE documentations SET section_ids_order = ? WHERE id = ?", array($params["sections_order"], $params["documentation_id"]));
                    
                    if($update_docs_section_order){
                        $response_data["status"] = true;
                    }
                }
                else{
                    throw new Exception($documentation["error"]);
                }
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }
    }
?>