<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Module extends CI_Model {
        # DOCU: This function will fetch the module data
        # Triggered by: Any models that needs to fetch data of a module
        # Requires: $module_id
        # Returns: { status: true/false, result: { module_data }, error: null }
        # Last updated at: March 29, 2023
        # Owner: Erick, Updated by: Jovic
        public function getModule($module_id){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $get_module = $this->db->query("
                    SELECT 
                        modules.id, modules.tab_ids_order,
                        JSON_ARRAYAGG(tabs.id) AS tab_ids
                    FROM modules
                    INNER JOIN tabs ON tabs.module_id = modules.id
                    WHERE modules.id = ?;
                ", $module_id);
                
                if($get_module->num_rows()){                    
                    $response_data["status"] = true;
                    $response_data["result"] = $get_module->result_array()[FIRST_INDEX];
                }
                else{
                    throw new Exception("No module found.");
                }      
            }

            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }
        
        # DOCU: This function will fetch the tab data depending if in json format or normal reult
        # Triggered by: Any models that needs to fetch data of a tab
        # Requires: $tab_id
        # Returns: { status: true/false, result: { module_data }, error: null }
        # Last updated at: March 15, 2023
        # Owner: Erick
        public function getTab($tab_id, $json_format=false){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {

                $select_query_tab = ($json_format) ? 
                "SELECT
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
                    ) AS module_tabs_json 
                FROM tabs
                WHERE tabs.id = ?" : "SELECT id, module_id, title, content, cache_posts_count, is_comments_allowed FROM tabs WHERE id = ?";

                $get_tab = $this->db->query($select_query_tab, $tab_id);
                
                if($get_tab->num_rows()){                    
                    $response_data["status"] = true;
                    $response_data["result"] = $get_tab->result_array()[FIRST_INDEX];
                }
                else{
                    throw new Exception("No module found.");
                }      
            }

            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will add new module and initial tab of the section
        # Triggered by: (POST) module/add
        # Requires: $params { section_id }
        # Returns: { status: true/false, result: { module_id, html }, error: null }
        # Last updated at: April 17, 2023
        # Owner: Erick, Updated by: Jovic
        public function addModule($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                # Check if User is an Admin
                if($_SESSION["user_level_id"] == USER_LEVEL["ADMIN"]){
                    # Fetch section
                    $this->load->model("Section");
                    $section = $this->Section->getSection(array("section_id" => $params["section_id"]));
    
                    if($section["status"]){
                        $insert_module_record = $this->db->query(
                            "INSERT INTO modules (section_id, user_id, created_at, updated_at) VALUES (?, ?, NOW(), NOW())", array($params["section_id"], $_SESSION["user_id"])
                        );
    
                        $new_module_id = $this->db->insert_id($insert_module_record);
    
                        # Check if the new section is successfully added
                        if($new_module_id > ZERO_VALUE){
                            # Create new tab after creating a new module
                            $insert_tab_record = $this->db->query("
                                INSERT INTO tabs (module_id, user_id, title, is_comments_allowed, cache_posts_count, created_at, updated_at) 
                                VALUES (?, ?, ?, ?, ?, NOW(), NOW())", array($new_module_id , $_SESSION["user_id"], "Untitled", NO, ZERO_VALUE)
                            );
    
                            $new_tab_id = $this->db->insert_id($insert_tab_record);
    
                            # Check if new tab is successfully created
                            if($new_tab_id > ZERO_VALUE){
                                # Update sections record
                                $update_section = $this->updateSection($params["section_id"]);

                                if($update_section["status"]){
                                    # Check module have tab_ids_order
                                    $module = $this->getModule($new_module_id);
                                    
                                    if($module["status"]){
                                        $new_tab_ids_order = ($module["result"]["tab_ids_order"] == NULL) ? $new_tab_id : $module["result"]["tab_ids_order"].",".$new_tab_id;
                                        
                                        # After new tab is created, updated the tab_ids_order of modules table
                                        $update_modules_tab_order = $this->db->query("UPDATE modules SET tab_ids_order = ? WHERE id = ?", array($new_tab_ids_order, $new_module_id));
        
                                        if($update_modules_tab_order){
                                            $section_modules = $this->Section->getSectionTabs($params["section_id"], $new_module_id);
                                            
                                            # Check if new modules exist with the data of tab json
                                            if($section_modules["status"]){
                                                $response_data["status"] = true;
                                                $response_data["result"] = array(
                                                    "module_id" => $new_module_id,
                                                    "html"      => $this->load->view('partials/section_page_content_partial.php', array("modules" => $section_modules["result"]), true)
                                                );
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    else{
                        throw new Exception($section["error"]);
                    }   
                }
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }
        
        # DOCU: This function will add new tab in a module
        # Triggered by: (POST) module/add_tab
        # Requires: $params { section_id }
        # Returns: { status: true/false, result: { module_id, tab_id, html_tab, html_content }, error: null }
        # Last updated at: April 17, 2023
        # Owner: Erick, Updated by: Jovic
        public function addTab($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                # Check if User is an Admin
                if($_SESSION["user_level_id"] == USER_LEVEL["ADMIN"]){
                    # Fetch module
                    $module = $this->getModule($params["module_id"]);
    
                    # Check if module exists
                    if($module["status"] && $module["result"]["id"]){
                        $module_id = $module["result"]["id"];
    
                        $insert_tab_record = $this->db->query("
                            INSERT INTO tabs (module_id, user_id, title, is_comments_allowed, cache_posts_count, created_at, updated_at) 
                            VALUES (?, ?, ?, ?, ?, NOW(), NOW())", array($module_id, $_SESSION["user_id"], "Untitled", NO, ZERO_VALUE)
                        );
    
                        $new_tab_id = $this->db->insert_id($insert_tab_record);
    
                        # Check if new tab is successfully created
                        if($new_tab_id > ZERO_VALUE){
                            # Check if tab_ids_order exists
                            if($module["result"]["tab_ids_order"]){
                                # Create new tab_ids_order in the module
                                $new_tab_ids_order = $module["result"]["tab_ids_order"].",".$new_tab_id;
                                
                                # After new tab is created, updated the tab_ids_order of modules table
                                $update_modules_tab_order = $this->db->query("UPDATE modules SET tab_ids_order = ? WHERE id = ?", array($new_tab_ids_order, $module_id));

                                if(!$this->db->affected_rows()){
                                    throw new Exception("Error while updateding Modules record");
                                }
                            }

                            # Update sections record
                            $update_section = $this->updateSection($params["section_id"]);

                            if($update_section["status"]){
                                # Generate updated module_tabs_json
                                $new_tab_json = $this->getTab($new_tab_id, true);
                                $module_tabs_json = json_decode($new_tab_json["result"]["module_tabs_json"]);
                                
                                $response_data["status"] = true;
                                $response_data["result"] = array(
                                    "module_id"     => $module_id,
                                    "tab_id"        => $new_tab_id,
                                    "html_tab"      => $this->load->view("partials/page_tab_item_partial.php", array("section_id" => $params["section_id"], "module_tabs_json" => $module_tabs_json, "tab_ids_order" => array($new_tab_id)), true),
                                    "html_content"  => $this->load->view("partials/section_page_tab_partial.php", array("section_id" => $params["section_id"], "module_tabs_json" => $module_tabs_json, "tab_ids_order" => array($new_tab_id)), true)
                                );
                            }
                        }
                    }
                    else{
                        throw new Exception($module["error"]);
                    }
                }
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will update Module depending on the type of $params['action'] given
        # Triggered by: (POST) module/update
        # Requires: $params {action, module_title, module_content, is_comments_allowed, tab_id }, $_SESSION["user_id"]
        # Returns: { status: true/false, result: {}, error: null }
        # Last updated at: April 17, 2023
        # Owner: Jovic, Updated by: Jovic
        public function updateModule($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                # Check if User is an Admin
                if($_SESSION["user_level_id"] == USER_LEVEL["ADMIN"]){
                    # Start DB transaction
                    $this->db->trans_start();
                    $update_tab = $this->db->query("UPDATE tabs SET title = ?, content = ?, is_comments_allowed = ?, updated_by_user_id = ?, updated_at = NOW() WHERE id = ?;", 
                    array($params["module_title"], $params["module_content"], $params["is_comments_allowed"], $_SESSION["user_id"], $params["tab_id"]));
    
                    
                    if($this->db->affected_rows()){
                        # Update sections record
                        $update_section = $this->updateSection($params["section_id"]);

                        if($update_section["status"]){
                            # Check if module_content has files or images
                            preg_match_all('~(?<=href=").*?(?=")|(?<=src=").*?(?=")~', $params["module_content"], $included_links);
                            
                            if(count($included_links[FIRST_INDEX])){
                                $included_links = array_unique($included_links[FIRST_INDEX]);
                                
                                # Fetch Files whose tab_ids contains $params["tab_id"] 
                                $get_files = $this->db->query("SELECT JSON_ARRAYAGG(id) AS file_ids, JSON_ARRAYAGG(file_url) AS file_urls, JSON_ARRAYAGG(tab_ids) AS file_tab_ids FROM files WHERE tab_ids REGEXP ?;", "[[:<:]]{$params["tab_id"]}[[:>:]]");
                                
                                if($get_files->num_rows()){
                                    $get_files = $get_files->result_array()[FIRST_INDEX];
        
                                    # Prepare needed arrays
                                    $file_ids      = json_decode($get_files["file_ids"]);
                                    $file_urls     = json_decode($get_files["file_urls"]);
                                    $file_tab_ids  = json_decode($get_files["file_tab_ids"]);
                                    $values_clause = $bind_params = array();
        
                                    # Check files to remove
                                    if($file_urls){
                                        # Check for removed file_urls
                                        $files_to_remove = array_diff($file_urls, $included_links);
            
                                        if($files_to_remove){
                                            # Prepare query values
                                            foreach($files_to_remove as $key => $file){
                                                # Get index of file
                                                $file_index = array_search($file, $file_urls);
                                                $tab_ids = explode(",", $file_tab_ids[$file_index]);
            
                                                # Remove tab_id if it's in File record's tab_ids
                                                $tab_index = array_search($params["tab_id"], $tab_ids);
                        
                                                if($tab_index !== FALSE){
                                                    unset($tab_ids[$tab_index]);
                        
                                                    # Convert array to comma-separated value then update File record
                                                    $tab_ids = implode(",", $tab_ids);
                                                    array_push($values_clause, "(?, ?)");
                                                    array_push($bind_params, $file_ids[$file_index], $tab_ids);
                                                }
                                            }
                                        }
                                    }
                                    else{
                                        # Fetch File records based on links in $included_links
                                        $get_files_ids = $this->db->query("SELECT id, tab_ids FROM files WHERE file_url IN ?;", array($included_links));

                                        if($get_files_ids->num_rows()){
                                            $get_files_ids = $get_files_ids->result_array();

                                            # Prepare query values
                                            foreach($get_files_ids as $file){
                                                $tabs_ids = $file["tab_ids"] ? "{$file["tab_ids"]},{$params["tab_id"]}" : $params["tab_id"];

                                                array_push($values_clause, "(?, ?)");
                                                array_push($bind_params, $file["id"], $tabs_ids);
                                            }
                                        }
                                    }

                                    # Update File record/s
                                    if($values_clause && $bind_params){
                                        $values_clause = implode(",", $values_clause);
                                        $update_files = $this->db->query("INSERT INTO files (id, tab_ids) VALUES {$values_clause} ON DUPLICATE KEY UPDATE tab_ids = VALUES(tab_ids)", $bind_params);
                    
                                        if(!$update_files){
                                            throw new Exception("Error updating File records");
                                        }
                                    }
                                }
                            }
                            else{
                                # Delete tab_id from File records' tab_ids
                                $remove_file_tab_id = $this->removeFileTabId($params["tab_id"]);
                            }
        
                            # Commit changes to DB
                            $this->db->trans_complete();
                            
                            $response_data["status"] = true;
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

        # DOCU: This function will delete the tab of a module
        # Triggered by: (POST) module/remove_tab
        # Requires: $params { tab_id }
        # Returns: { status: true/false, result: { tab_id }, error: null }
        # Last updated at: April 19, 2023
        # Owner: Erick, Updated by: Jovic
        public function removeTab($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                # Check if User is an Admin
                if($_SESSION["user_level_id"] == USER_LEVEL["ADMIN"]){
                    # Start DB transaction
                    $this->db->trans_start();
                    $tab = $this->getTab($params["tab_id"]);
    
                    if($tab["status"]){
                        # Fetch Posts of Tab
                        $get_posts = $this->db->query("SELECT JSON_ARRAYAGG(id) AS posts_ids FROM posts WHERE tab_id = ?;", $params["tab_id"]);

                        # Proceed to delete Comments & Posts of Tab
                        if($get_posts->num_rows()){
                            $posts_ids = json_decode($get_posts->result_array()[FIRST_INDEX]["posts_ids"]);

                            # Delete Comments of Posts
                            $delete_comments = $this->db->query("DELETE FROM comments WHERE post_id IN ?;", array($posts_ids));

                            # Delete Posts
                            if($delete_comments){
                                $delete_posts = $this->db->query("DELETE FROM posts WHERE id IN ?;", array($posts_ids));

                                if(!$delete_posts){
                                    throw new Exception("Error deleting Posts");
                                }
                            }
                            else{
                                throw new Exception("Error deleting Comments");
                            }
                        }

                        # Delete Tab
                        $delete_section = $this->db->query("DELETE FROM tabs WHERE id = ?;", $params["tab_id"]);
    
                        if($delete_section){
                            $module = $this->getModule($tab["result"]["module_id"]);
    
                            if($module["status"]){
                                $tab_ids = json_decode($module["result"]["tab_ids"]);
                                
                                # Update tab_ids_order if it exist
                                if($module["result"]["tab_ids_order"]){
                                    # Remove section_id from section_ids_order and update documentation record
                                    $tabs_order = explode(",", $module["result"]["tab_ids_order"]);
                                    $tab_index  = array_search($params["tab_id"], $tabs_order);
        
                                    if($tab_index !== FALSE){
                                        unset($tabs_order[$tab_index]);
                                        $tabs_order = count($tabs_order) ? implode(",", $tabs_order) : "";
        
                                        # Update documentations section_ids_order
                                        $update_module_tabs_order = $this->db->query("UPDATE modules SET tab_ids_order = ? WHERE id = ?", array($tabs_order, $tab["result"]["module_id"]));
        
                                        if(!$update_module_tabs_order){
                                            throw new Exception("Error updating tab_ids_order");
                                        }
                                    }
                                }
                                
                                # Check if we are deleting the last tab then remove the module also.
                                if(!$tab_ids){
                                    $delete_module = $this->db->query("DELETE FROM modules WHERE id = ?;", $tab["result"]["module_id"]);
        
                                    if(!$delete_module){
                                        throw new Exception("Error deleting module");
                                    }
                                }

                                # Update sections record
                                $update_section = $this->updateSection($params["section_id"]);

                                if($update_section["status"]){
                                    # Check if we need to remove tab_id in Files record
                                    $remove_file_tab_id = $this->removeFileTabId($params["tab_id"]);
            
                                    if($remove_file_tab_id["status"]){
                                        # Commit changes to DB
                                        $this->db->trans_complete();
            
                                        $response_data["status"] = true;
                                        $response_data["result"]["tab_id"] = $tab["result"]["id"];
                                        $response_data["result"]["remove_file_tab_id"] = $remove_file_tab_id;
                                    }
                                    else{
                                        throw new Exception("Error removing Tab");
                                    }
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

        # DOCU: This function will reoder the tabs of a module
        # Triggered by: (POST) module/reorder_tab
        # Requires: $params { module_id, tab_ids_order }
        # Returns: { status: true/false, result: { tab_id }, error: null }
        # Last updated at: April 17, 2023
        # Owner: Erick, Updated by: Jovic
        public function reorderTab($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                # Check if User is an Admin
                if($_SESSION["user_level_id"] == USER_LEVEL["ADMIN"]){
                    $update_module_tab_ids_order = $this->db->query("UPDATE modules SET tab_ids_order = ?, updated_at = NOW() WHERE id = ?;", array($params["tab_ids_order"], $params["module_id"]));
    
                    if($this->db->affected_rows()){
                        # Update sections record
                        $update_section = $this->updateSection($params["section_id"]);

                        if($update_section["status"]){
                            $response_data["status"] = true;
                        }
                    }
                    else{
                        throw new Exception("Unable to update order of the tabs.");
                    }
                }
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will duplicate modules of a documentation
        # Triggered by: (POST) docs/duplicate
        # Requires: $params { documentation_id, duplicate_section_ids }, $_SESSION["user_id"]
        # Returns: { status: true/false, result: {}, error: null }
        # Last updated at: March 24, 2023
        # Owner: Jovic
        public function duplicateModules($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                # Check if documentation_id is present
                if(isset($params["documentation_id"])){
                    # Fetch documentation section_ids
                    $get_section_ids = $this->db->query("SELECT JSON_ARRAYAGG(id) AS section_ids FROM sections WHERE documentation_id = ?;", $params["documentation_id"]);
    
                    if($get_section_ids->num_rows()){
                        # Fetch documentation modules
                        $section_ids = json_decode($get_section_ids->result_array()[FIRST_INDEX]["section_ids"]);
                    }
                }
                else{
                    $section_ids = array($params["section_id"]);
                    $params["duplicate_section_ids"] = array($params["new_section_id"]);
                }

                # Fetch documentation modules
                $get_modules = $this->db->query("SELECT COUNT(id) AS modules_count FROM modules WHERE section_id IN ? GROUP BY section_id;", array($section_ids));

                if($get_modules->num_rows()){
                    # create values_clause for creating modules for duplicated documentation
                    $get_modules   = $get_modules->result_array();
                    $values_clause = $bind_params = array();

                    for($modules_index = 0; $modules_index < count($get_modules); $modules_index++){
                        for($index = 0; $index < $get_modules[$modules_index]["modules_count"]; $index++){
                            array_push($values_clause, "(?, ?, NOW(), NOW())");
                            array_push($bind_params, $params["duplicate_section_ids"][$modules_index], $_SESSION["user_id"]);
                        }
                    }

                    # Finalize values_clause
                    $values_clause = implode(",", $values_clause);
                    $create_modules = $this->db->query("INSERT INTO modules (section_id, user_id, created_at, updated_at) VALUES {$values_clause};", $bind_params);
                
                    if($create_modules){
                        $get_created_modules = $this->db->query("SELECT JSON_ARRAYAGG(id) AS module_ids FROM modules WHERE section_id IN ?;", array($params["duplicate_section_ids"]));

                        if($get_created_modules->num_rows()){
                            $response_data["status"] = true;
                            $response_data["result"]["module_ids"] = json_decode($get_created_modules->result_array()[FIRST_INDEX]["module_ids"]);
                        }

                        $response_data["status"] = true;
                    }
                }
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }
        
        # DOCU: This function will duplicate tabs of a documentation
        # Triggered by: (POST) docs/duplicate
        # Requires: $params { documentation_id, module_ids }, $_SESSION["user_id"]
        # Returns: { status: true/false, result: {}, error: null }
        # Last updated at: March 24, 2023
        # Owner: Jovic
        public function duplicateTabs($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                # Fetch all modules and tabs of Documentation or Section
                if(isset($params["documentation_id"])){
                    $where_statement = "sections.documentation_id = ?";
                    $bind_param      = $params["documentation_id"];
                }
                else{
                    $where_statement = "sections.id = ?";
                    $bind_param      = $params["section_id"];
                }
                
                $get_tabs = $this->db->query("
                    SELECT
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
                        GROUP BY tabs.module_id
                    ) AS module_tabs ON module_tabs.module_id = modules.id
                    WHERE {$where_statement}
                    GROUP BY modules.id;", $bind_param
                );

                if($get_tabs->num_rows()){
                    $get_tabs = $get_tabs->result_array();

                    # Loop through Duplicated Modules to start Duplicating Tabs of Documentation
                    $values_clause = $bind_params = array();

                    for($index = 0; $index < count($params["module_ids"]); $index++) {
                        # json_decode is used to remove brackets from tab_ids_order
                        # tab_ids_order has brackets if we're fetching tab_ids from a duplicated Documentation
                        $tab_ids = (strpos($get_tabs[$index]["tab_ids_order"], "]") > ZERO_VALUE) ? json_decode($get_tabs[$index]["tab_ids_order"]) : explode(",", $get_tabs[$index]["tab_ids_order"]);

                        $tabs_json = json_decode($get_tabs[$index]["module_tabs_json"]);

                        foreach($tab_ids as $tab_id){
                            $current_tab = $tabs_json->$tab_id;
                            array_push($values_clause, "(?, ?, ?, ?, ?, ?, NOW(), NOW())");
                            array_push($bind_params, 
                                $params["module_ids"][$index], 
                                $_SESSION["user_id"], 
                                $current_tab->title, 
                                $current_tab->content, 
                                $_SESSION["user_id"], 
                                $current_tab->is_comments_allowed
                            );
                        }
                    }

                    # Finalize values_clause
                    $values_clause = implode(",", $values_clause);
                    
                    # Create Tabs for Duplicated Documentation
                    $create_tabs = $this->db->query("
                        INSERT INTO tabs (module_id, user_id, title, content, updated_by_user_id, is_comments_allowed, created_at, updated_at)
                        VALUES {$values_clause};", $bind_params
                    );

                    if($create_tabs){
                        $response_data["status"] = true;
                    }
                }
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will remove records from a given table
        # Triggered by: (POST) docs/remove
        # Requires: $params { table, ids }
        # Returns: { status: true/false, result: {}, error: null }
        # Last updated at: March 20, 2023
        # Owner: Jovic
        public function removeRecords($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $remove_records = $this->db->query("DELETE FROM {$params['table']} WHERE id IN ?;", array($params['ids']));

                if($remove_records){
                    $response_data["status"] = true;
                }
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }
        
        # DOCU: This function will link file into a tabs to determine files are being used in a tab
        # Triggered by: (POST) modules/link_file_tab`
        # Requires: $params { file_id, tab_id  }
        # Returns: { status: true/false, result: { file_id }, error: null }
        # Last updated at: April 13, 2023
        # Owner: Erick, Updated by: Jovic
        public function linkFileTab($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                # Check if User is an Admin
                if($_SESSION["user_level_id"] == USER_LEVEL["ADMIN"]){
                    $this->load->model("File");
                    $file = $this->File->getFile(array("file_id" => $params["file_id"]));
    
                    if($file["status"] && $file["result"]){
                        $tab_ids = $file["result"]["tab_ids"];
                        $explode_tab_ids = explode(",", $tab_ids);
    
                        /* Check if the file is not yet associated to tabs to prevent duplicate tab_ids */
                        if(!(in_array(strval($params["tab_id"]), $explode_tab_ids))){
                            $new_tab_ids = ($tab_ids) ? $tab_ids.','.$params["tab_id"] : $params["tab_id"];
                            $update_file_tab_ids = $this->db->query("UPDATE files SET tab_ids = ? WHERE id = ?;", array($new_tab_ids, $params["file_id"]));
        
                            if($this->db->affected_rows()){
                                $response_data["status"]            = true;
                                $response_data["result"]["file_id"] = $params["file_id"];
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

        # DOCU: This function will remove tab_id from tab_ids in Files table
        # Triggered by: updateModule(), removeTab()
        # Requires: $tab_id
        # Returns: { status: true/false, result: {}, error: null }
        # Last updated at: March 28, 2023
        # Owner: Jovic
        private function removeFileTabId($tab_id){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $this->load->model("File");
                $get_files = $this->File->getFiles(array("tab_id" => $tab_id));

                if($get_files["status"] && $get_files["result"]){
                    $values_clause = $bind_params = array();

                    # Prepare query values
                    foreach($get_files["result"] as $file){
                        $tab_ids = explode(",", $file["tab_ids"]);

                        # Remove tab_id if it's in File record's tab_ids
                        $tab_index = array_search($tab_id, $tab_ids);

                        if($tab_index !== FALSE){
                            unset($tab_ids[$tab_index]);

                            # Convert array to comma-separated value then update File record
                            $tab_ids = implode(",", $tab_ids);
                            array_push($values_clause, "(?, ?)");
                            array_push($bind_params, $file["file_id"], $tab_ids);
                        }
                    }

                    $values_clause = implode(",", $values_clause);
                    $update_files = $this->db->query("INSERT INTO files (id, tab_ids) VALUES {$values_clause} ON DUPLICATE KEY UPDATE tab_ids = VALUES(tab_ids)", $bind_params);

                    if(!$update_files){
                        throw new Exception("Error updating File records");
                    }
                }

                $response_data["status"] = true;
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will update section record after changes are made to Modules and Tabs record
        # Triggered by: addModule(), addTab(), updateModule(), removeTab(), reoderTab()
        # Requires: $section_id
        # Returns: { status: true/false, result: {}, error: null }
        # Last updated at: April 17, 2023
        # Owner: Jovic
        private function updateSection($section_id){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try{
                $this->db->query("UPDATE sections SET updated_by_user_id = ?, updated_at = NOW() WHERE id = ?;", array($_SESSION["user_id"], $section_id));

                if($this->db->affected_rows()){
                    $response_data["status"] = true;
                }
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }
    }
?>