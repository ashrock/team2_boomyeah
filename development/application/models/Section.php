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
        # Last updated at: March 27, 2023
        # Owner: Erick, Updated by: Jovic
        public function getSection($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $where_clause = "id = ?";
                $bind_params  = $params["section_id"];

                if(isset($params["documentation_id"])){
                    $where_clause = "id = ? AND documentation_id = ?";
                    $bind_params  = $params;
                }

                $get_section = $this->db->query("SELECT id, documentation_id, title, description FROM sections WHERE {$where_clause}", $bind_params);
                
                if($get_section->num_rows()){                    
                    $response_data["status"] = true;
                    $response_data["result"] = $get_section->result_array()[FIRST_INDEX];
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
        # Last updated at: March 28, 2023
        # Owner: Jovic, Updated by: Erick, Jovic
        public function getSectionTabs($section_id, $module_id = ZERO_VALUE){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $where_statement = ($module_id) ? "AND modules.id = ?" : "";
                $where_values    = ($module_id) ? array($module_id, $section_id, $module_id) : array($section_id);
                $group_by_module_id = ($module_id) ? "WHERE tabs.module_id = ?" : "GROUP BY module_id";

                $get_section = $this->db->query("
                    SELECT
                        sections.id AS section_id,
                        modules.id AS module_id, 
                        (CASE
                            WHEN modules.tab_ids_order IS NOT NULL THEN 
                                modules.tab_ids_order
                            ELSE
                                JSON_ARRAYAGG(tabs.id)
                        END) AS tab_ids_order,
                        ANY_VALUE(module_tabs.tabs) AS module_tabs_json
                    FROM sections
                    INNER JOIN modules ON modules.section_id = sections.id
                    LEFT JOIN tabs ON tabs.module_id = modules.id
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
                        {$group_by_module_id} 
                    ) AS module_tabs ON module_tabs.module_id = modules.id
                    WHERE sections.id = ? {$where_statement}
                    GROUP BY modules.id;
                ", $where_values);

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
        # Last updated at: March 27, 2023
        # Owner: Erick, Updated by: Jovic
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
                        $section_order = $get_documentation["result"]["section_ids_order"];

                        # Update section_ids_order if it exists
                        if($section_order){
                            # Append the new section id in documentation section_ids_order
                            $new_section_order  = ($section_order) ? $section_order.','.$new_section_id : $new_section_id;

                            if($new_section_order){
                                # Update the new section_ids_order in documentations table
                                $update_documentation = $this->db->query("
                                    UPDATE documentations SET section_ids_order = ?, updated_at=NOW(), updated_by_user_id =? WHERE id = ?", 
                                    array($new_section_order, $_SESSION["user_id"], $params["documentation_id"])
                                );

                                # Check if section_ids_order is successfully updated
                                if(!$update_documentation){
                                    throw new Exception("Failed to update Documentation Record.");
                                }
                            }
                        }
                        
                        $new_section = $this->getSection(array("section_id" => $new_section_id));

                        if($new_section["status"]){
                            $response_data["status"]               = true;
                            $response_data["result"]["section_id"] = $new_section_id;
                            $response_data["result"]["html"]       = $this->load->view('partials/section_block_partial.php', array("all_sections" => array($new_section["result"])), true);
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
        # Last updated at: March 20, 2023
        # Owner: Jovic
        public function duplicateSections($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                # Create sections
                $order_by_clause = $params['section_ids_order'] ? " ORDER BY FIELD(id, {$params['section_ids_order']})": "";
                $create_sections = $this->db->query("
                    INSERT INTO sections (documentation_id, user_id, title, description, created_at, updated_at)
                    SELECT ?, ?, title, description, NOW(), NOW() FROM sections WHERE documentation_id = ?{$order_by_clause};",
                    array($params["duplicate_id"], $_SESSION["user_id"], $params["documentation_id"])
                );

                if($create_sections){
                    $get_sections = $this->db->query("SELECT JSON_ARRAYAGG(id) AS section_ids FROM sections WHERE documentation_id = ?", $params["duplicate_id"]);

                    if($get_sections->num_rows()){
                        $response_data["status"] = true;
                        $response_data["result"]["section_ids"] = json_decode($get_sections->result_array()[FIRST_INDEX]["section_ids"]);
                    }
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
        # Last updated at: March 27, 2023
        # Owner: Erick, Updated by: Jovic
        public function updateSection($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $section = $this->getSection(array("section_id" => $params["section_id"]));
                
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
        # Last updated at: March 27, 2023
        # Owner: Erick, Updated by: Jovic
        public function duplicateSection($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $this->db->trans_start();
                $section = $this->getSection(array("section_id" => $params["section_id"]));
                
                # Check document id if existing
                if($section["status"]){
                    $section_data = $section["result"];

                    # Fetch section_ids of Documentation
                    $section_ids = $this->db->query("SELECT JSON_ARRAYAGG(id) AS section_ids FROM sections WHERE documentation_id = ?;", $section_data["documentation_id"]);
                    $section_ids = $section_ids->result_array()[FIRST_INDEX]["section_ids"];
                    $section_ids = json_decode($section_ids);

                    $insert_duplicate_section_record = $this->db->query("
                        INSERT INTO sections (documentation_id, user_id, title, description, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())",
                        array($section_data["documentation_id"], $_SESSION["user_id"], "Copy of {$section_data['title']}", $section_data["description"])
                    );

                    $new_section_id = $this->db->insert_id($insert_duplicate_section_record);

                    if($new_section_id > ZERO_VALUE){
                        $this->load->model("Documentation");
                        $documentation = $this->Documentation->getDocumentation($section_data["documentation_id"]);

                        if($documentation["status"]){
                            $new_sections_order = $documentation["result"]["section_ids_order"] ? explode(",", $documentation["result"]["section_ids_order"]) : $section_ids;
                            
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
                                # Duplicate Module
                                $new_section = $this->getSection(array("section_id" => $new_section_id));

                                if($new_section["status"]){
                                    $this->load->model("Module");
                                    $duplicate_modules = $this->Module->duplicateModules(array(
                                        "section_id"     => $params["section_id"],
                                        "new_section_id" => $new_section_id
                                    ));
                                    
                                    # Check if there are any Module duplicated then proceed to duplicating Tabs
                                    if($duplicate_modules["status"] && $duplicate_modules["result"]["module_ids"] ){
                                        # Create tabs
                                        $duplicate_tabs = $this->Module->duplicateTabs(array(
                                            "section_id" => $params["section_id"], 
                                            "module_ids" => $duplicate_modules["result"]["module_ids"]
                                        ));
                                    }

                                    $this->db->trans_complete();
                                    $response_data["status"] = true;
                                    $response_data["result"]["section_id"] = $new_section_id;
                                    $response_data["result"]["html"] = $this->load->view('partials/section_block_partial.php', array("all_sections" => array($new_section["result"])), true);
                                }
                            }
                        }
                    }
                }
            }
            catch (Exception $e) {
                $this->db->trans_rollback();
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will delete a section
        # Triggered by: (POST) docs/remove
        # Requires: $params { documentation_id, section_id }
        # Returns: { status: true/false, result: {}, error: null }
        # Last updated at: March 27, 2023
        # Owner: Erick, Updated by: Jovic
        public function removeSection($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                # Start DB transaction
                $this->db->trans_start();

                $section = $this->getSection(array("section_id" => $params["section_id"]));
               
                # Check section if existing
                if($section["status"]){
                    # Fetch record_ids related to Section
                    $get_record_ids = $this->db->query("
                        SELECT
                            JSON_ARRAYAGG(modules.id) AS module_ids,
                            JSON_ARRAYAGG(tabs.id) AS tab_ids,
                            JSON_ARRAYAGG(posts.id) AS post_ids,
                            JSON_ARRAYAGG(comments.id) AS comment_ids
                        FROM sections
                        LEFT JOIN modules ON modules.section_id = sections.id
                        LEFT JOIN tabs ON tabs.module_id = modules.id
                        LEFT JOIN posts ON posts.tab_id = tabs.id
                        LEFT JOIN comments ON comments.post_id = posts.id
                        WHERE sections.id = ?;", $params["section_id"]
                    );

                    if($get_record_ids->num_rows()){
                        $get_record_ids = $get_record_ids->result_array()[FIRST_INDEX];
    
                        $this->load->model("Module");
                        $related_tables = array(
                            "comments" => "comment_ids",
                            "posts"    => "post_ids",
                            "tabs"     => "tab_ids",
                            "modules"  => "module_ids"
                        );
    
                        # Delete comments, posts, tabs, and modules
                        foreach($related_tables as $key => $value){
                            # Check if record ids is not null
                            $record_ids = array_filter(json_decode($get_record_ids[$value]));
    
                            if($record_ids){
                                $remove_records = $this->Module->removeRecords(array("table" => $key, "ids" => $record_ids));
                            
                                if(!$remove_records["status"]){
                                    throw new Exception($remove_records["error"]);
                                }
                            }
                        }

                        # Get files of Section
                        $this->load->model("File");
                        $get_files = $this->File->getFiles(array("section_id" => $params["section_id"]));

                        if($get_files["status"] && $get_files["result"]){
                            $file_ids   = array();
                            $file_urls  = array();

                            foreach($get_files["result"] as $file){
                                array_push($file_ids, $file["file_id"]);
                                array_push($file_urls, $file["file_url"]);
                            }
                            
                            # Delete files in DB and S3
                            $delete_files = $this->File->removeFiles(array("file_ids" => $file_ids, "file_urls" => $file_urls));

                            if(!$delete_files["status"]){
                                throw new Exception($delete_files["error"]);
                            }
                        }

                        $delete_section = $this->db->query("DELETE FROM sections WHERE id = ?;", $params["section_id"]);
    
                        if($delete_section){
                            # Fetch section_ids_order
                            $this->load->model("Documentation");
                            $documentation = $this->Documentation->getDocumentation($params["documentation_id"]);
        
                            if($documentation["status"]){
                                # Check of section_ids_order exists
                                if($documentation["result"]["section_ids_order"]){
                                    # Remove section_id from section_ids_order and update documentation record
                                    $sections_order = explode(",", $documentation["result"]["section_ids_order"]);
                                    $section_index  = array_search($params["section_id"], $sections_order);
                                    
                                    if($section_index !== FALSE){
                                        unset($sections_order[$section_index]);
                                        $sections_count = count($sections_order);
                                        $sections_order = ($sections_count) ? implode(",", $sections_order) : "";
        
                                        # Update documentations section_ids_order
                                        $update_docs_section_order = $this->db->query("UPDATE documentations SET section_ids_order = ? WHERE id = ?", array($sections_order, $params["documentation_id"]));
                                    
                                        if(!$update_docs_section_order){
                                            throw new Exception("Error updating Documentation");
                                        }
                                    }
                                    else{
                                        $this->db->trans_rollback();
                                        throw new Exception("Unable to delete section, the section is not included in the section_ids_order field.");
                                    }
                                }
    
                                # Commit changes to DB
                                $this->db->trans_complete();
        
                                $response_data["status"] = true;
                                $response_data["result"]["section_id"] = $section["result"]["id"];
                            }
                            else{
                                throw new Exception($documentation["error"]);
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