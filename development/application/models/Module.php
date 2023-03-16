<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Module extends CI_Model {
        # DOCU: This function will fetch the module data
        # Triggered by: Any models that needs to fetch data of a module
        # Requires: $module_id
        # Returns: { status: true/false, result: { module_data }, error: null }
        # Last updated at: March 15, 2023
        # Owner: Erick
        public function getModule($module_id){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $get_module = $this->db->query("SELECT id, tab_ids_order FROM modules WHERE id = ?", $module_id);
                
                if($get_module->num_rows()){                    
                    $response_data["status"] = true;
                    $response_data["result"] = $get_module->result_array()[0];
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
                    $response_data["result"] = $get_tab->result_array()[0];
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
        # Last updated at: March 15, 2023
        # Owner: Erick
        public function addModule($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                # Fetch section
                $this->load->model("Section");
                $section = $this->Section->getSection($params["section_id"]);

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
                            VALUES (?, ?, ?, ?, ?, NOW(), NOW())", array($new_module_id , $_SESSION["user_id"], "Untitled Tab", NO, ZERO_VALUE)
                        );

                        $new_tab_id = $this->db->insert_id($insert_tab_record);

                        # Check if new tab is successfully created
                        if($new_tab_id > ZERO_VALUE){
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
                else{
                    throw new Exception($section["error"]);
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
        # Last updated at: March 15, 2023
        # Owner: Erick
        public function addTab($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                # Fetch module
                $module = $this->getModule($params["module_id"]);

                if($module["status"]){
                    $module_id = $module["result"]["id"];

                    $insert_tab_record = $this->db->query("
                        INSERT INTO tabs (module_id, user_id, title, is_comments_allowed, cache_posts_count, created_at, updated_at) 
                        VALUES (?, ?, ?, ?, ?, NOW(), NOW())", array($module_id, $_SESSION["user_id"], "Untitled Tab", NO, ZERO_VALUE)
                    );

                    $new_tab_id = $this->db->insert_id($insert_tab_record);

                    # Check if new tab is successfully created
                    if($new_tab_id > ZERO_VALUE){
                        # Create new tab_ids_order in the module
                        $new_tab_ids_order = $module["result"]["tab_ids_order"].",".$new_tab_id;
                        
                        # After new tab is created, updated the tab_ids_order of modules table
                        $update_modules_tab_order = $this->db->query("UPDATE modules SET tab_ids_order = ? WHERE id = ?", array($new_tab_ids_order, $module_id));

                        if($update_modules_tab_order){
                            $new_tab_json = $this->getTab($new_tab_id, true);
                            $module_tabs_json = json_decode($new_tab_json["result"]["module_tabs_json"]);
                            
                            $response_data["status"] = true;
                            $response_data["result"] = array(
                                "module_id"     => $module_id,
                                "tab_id"        => $new_tab_id,
                                "html_tab"      => $this->load->view("partials/page_tab_item_partial.php", array("module_tabs_json" => $module_tabs_json, "tab_ids_order" => array($new_tab_id)), true),
                                "html_content"  => $this->load->view("partials/section_page_tab_partial.php", array("module_tabs_json" => $module_tabs_json, "tab_ids_order" => array($new_tab_id)), true)
                            );
                        }
                    }
                }
                else{
                    throw new Exception($module["error"]);
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
        # Last updated at: March 15, 2023
        # Owner: Jovic
        public function updateModule($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                if($params["action"] == "update_module_tab"){
                    $update_tab = $this->db->query("UPDATE tabs SET title = ?, content = ?, is_comments_allowed = ?, updated_by_user_id = ?, updated_at = NOW() WHERE id = ?;", 
                    array($params["module_title"], $params["module_content"], $params["is_comments_allowed"], $_SESSION["user_id"], $params["tab_id"]));

                    if($update_tab){
                        $response_data["status"] = true;
                    }
                }
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }
    }
?>