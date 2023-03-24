<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    require APPPATH . "libraries/vendor/autoload.php";
    use Aws\S3\S3Client;

    class File extends CI_Model {
        # DOCU: This function will upload files to S3 and create Files record in DB
        # Triggered by: (POST) files/upload
        # Requires: $params {"files", "section_id"}
        # Returns: { status: true/false, result: { html, files_uploaded }, error: null }
        # Last updated at: March 24, 2023
        # Owner: Jovic
        public function uploadFile($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $this->db->trans_start();
                $files_to_upload = array();
                $file_urls       = array();
                $files_count     = count($params["files"]["name"]);
                $upload_results  = array();

                # Validate files
                for($index=0; $index<$files_count; $index++){
                    # Check if file is a PDF and file size is applicable
                    if($params["files"]["size"][$index] <= MAX_FILE_SIZE){
                        # Generate file name {documentation_id}_{section_id}{delimiter}{file_name}
                        $timestamp = strtotime(date('Y-m-d H:i:s'));
                        $file_type = pathinfo($params["files"]["name"][$index], PATHINFO_EXTENSION);
                        $file_name = pathinfo($params["files"]["name"][$index], PATHINFO_FILENAME);

                        $file_name = "{$file_name}_{$timestamp}.{$file_type}";

                        array_push($files_to_upload, array(
                            "file_name" => $params["files"]["name"][$index],
                            "s3_name"   => $file_name,
                            "tmp_file"  => $params["files"]["tmp_name"][$index],
                            "mime_type" => mime_content_type($params["files"]["tmp_name"][$index])
                        ));

                        array_push($file_urls, $file_name);
                    }
                    else{
                        $response_data["result"]["file"] = $params["files"]["name"][$index];
                        $response_data["result"]["size"] = $params["files"]["size"][$index];

                        $response_data["error"] = "File size is too large." ;
                    }
                }

                # Instantiate an Amazon S3 client.
                $this->config->load("api_config");

                $s3Client = new S3Client([
                    'version' => 'latest',
                    'region'  => $this->config->item("s3_region"),
                    'credentials' => [
                        'key'    => $this->config->item("s3_accessKeyId"),
                        'secret' => $this->config->item("s3_secretKey")
                    ]
                ]);

                # Upload files
                $values_clause = array();
                $bind_params   = array();

                if($files_to_upload){
                    foreach($files_to_upload as $file){
                        try{
                            $upload_to_s3 = $s3Client->putObject([
                                "Bucket" => $this->config->item("s3_bucket"),
                                "Key"    => ENVIRONMENT . "/{$file["s3_name"]}",
                                "Body"   => fopen($file["tmp_file"], "r"),
                                "ACL"    => "public-read"
                            ]);
            
                            if($upload_to_s3["@metadata"]["statusCode"] === 200){
                                # Generate values_clause and bind_params for insert query   
                                array_push($values_clause, "(?, ?, ?, ?, NOW(), NOW())");
                                array_push($bind_params, $params["section_id"], $file["file_name"], $file["s3_name"], $file["mime_type"]);
                            }
                        }
                        catch (Aws\S3\Exception\S3Exception $e) {
                            $response_data["result"]["file"] = $file["file_name"];
    
                            throw new Exception($e->getMessage());
                        }
                    }
    
                    # Create file records
                    $values_clause = implode(",", $values_clause);
                    $create_files = $this->db->query("
                        INSERT INTO files (section_id, file_name, file_url, mime_type, created_at, updated_at) 
                        VALUES {$values_clause};", $bind_params);
    
                    if(!$create_files){
                        throw new Exception("Error creating File record.");
                    }

                    # Fetch recently uploaded files
                    $get_files = $this->getFiles(array("section_id" => $params["section_id"], "file_urls" => $file_urls));
                    
                    if($get_files["status"]){
                        $response_data["result"]["html"]           = $this->load->view("partials/upload_section_items_partial.php", array("fetch_uploaded_files_data" => $get_files["result"]), true);
                        $response_data["result"]["files_uploaded"] = count($get_files["result"]);
                    }
                }

                $response_data["status"] = true;
                $this->db->trans_complete();
            }
            catch (Exception $e) {
                $this->db->trans_rollback();
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will fetch files based on params given
        # Triggered by: (POST) files/upload, (GET) docs/(:any)/(:any)/edit
        # Requires: $params {"section_id"}
        # Optionals: $params {"file_url"}
        # Returns: { status: true/false, result: array(), error: null }
        # Last updated at: March 23, 2023
        # Owner: Jovic
        public function getFiles($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try{
                $where_clause = "WHERE section_id = ?";
                $bind_params  = $params["section_id"];

                if(isset($params["file_urls"])){
                    $where_clause .= " AND file_url IN ?";
                    $bind_params = array($params["section_id"], array_values($params["file_urls"]));
                }

                $get_files = $this->db->query("
                    SELECT section_id, id AS file_id, file_name, file_url, mime_type
                    FROM files
                    {$where_clause};
                ", $bind_params);

                if($get_files->num_rows()){
                    $response_data["result"] = $get_files->result_array();
                }

                $response_data["status"] = true;
            }
            catch (Exception $e) {
                $this->db->trans_rollback();
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will remove file in S3 and DB
        # Triggered by: (POST) files/remove
        # Requires: $params {"file_id", "file_url"}
        # Returns: { status: true/false, result: array(), error: null }
        # Last updated at: March 24, 2023
        # Owner: Jovic
        public function removeFile($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try{
                $this->db->trans_start();

                # Instantiate an Amazon S3 client.
                $this->config->load("api_config");

                $remove_file = $this->db->query("DELETE FROM files WHERE id = ?;", $params["file_id"]);

                if($remove_file){
                    $s3Client = new S3Client([
                        'version' => 'latest',
                        'region'  => $this->config->item("s3_region"),
                        'credentials' => [
                            'key'    => $this->config->item("s3_accessKeyId"),
                            'secret' => $this->config->item("s3_secretKey")
                        ]
                    ]);
    
                    # Delete file in S3
                    $s3Client->deleteObject([
                        "Bucket" => $this->config->item("s3_bucket"),
                        "Key"    => ENVIRONMENT . "/{$params["file_url"]}"
                    ]);

                    $response_data["status"]            = true;
                    $response_data["result"]["file_id"] = $params["file_id"];
                    $this->db->trans_complete();
                }
            }
            catch (Exception $e) {
                $this->db->trans_rollback();
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will remove files in S3 and DB
        # Triggered by: (POST) files/remove
        # Requires: $params {"file_ids", "file_urls"}
        # Returns: { status: true/false, result: array(), error: null }
        # Last updated at: March 24, 2023
        # Owner: Jovic
        public function removeFiles($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try{
                $this->db->trans_start();

                # Delete files in DB
                if($params["file_ids"]){
                    $remove_files = $this->db->query("DELETE FROM files WHERE id IN ?;", array($params["file_ids"]));

                    if($remove_files){
                        # Instantiate an Amazon S3 client.
                        $this->config->load("api_config");

                        $s3Client = new S3Client([
                            'version' => 'latest',
                            'region'  => $this->config->item("s3_region"),
                            'credentials' => [
                                'key'    => $this->config->item("s3_accessKeyId"),
                                'secret' => $this->config->item("s3_secretKey")
                            ]
                        ]);

                        # Delete files in S3
                        if($params["file_urls"]){
                            foreach($params["file_urls"] as $file_url){
                                $s3Client->deleteObject([
                                    "Bucket" => $this->config->item("s3_bucket"),
                                    "Key"    => ENVIRONMENT . "/{$file_url}"
                                ]);
                            }
                        }
                    }
                }

                $response_data["status"] = true;
                $this->db->trans_complete();
            }
            catch (Exception $e) {
                $this->db->trans_rollback();
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }
    }
?>