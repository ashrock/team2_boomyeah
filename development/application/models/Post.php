<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Post extends CI_Model {
        # DOCU: This function will fetch Post data and generate html
        # Triggered by: (POST) posts/add
        # Requires: $tab_id
        # Returns: { status: true/false, result: { tab_id, html }, error: null }
        # Last updated at: April 17, 2023
        # Owner: Jovic, Updated by: Jovic
        public function getPost($post_id){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $get_post = $this->db->query("
                    SELECT
                        posts.id AS post_id, posts.tab_id, users.id AS user_id, CONCAT(users.first_name, ' ', users.last_name) AS first_name, CONVERT_TZ(posts.updated_at, @@session.time_zone, '+00:00') AS date_posted,
                        posts.message, posts.cache_comments_count, users.profile_picture AS user_profile_pic,
                        (CASE WHEN posts.created_at != posts.updated_at THEN 1 ELSE 0 END) AS is_edited
                    FROM posts
                    INNER JOIN users ON users.id = posts.user_id
                    WHERE posts.id = ?
                    ORDER BY posts.id DESC;", $post_id
                );

                if($get_post->num_rows()){
                    $get_post = $get_post->result_array()[FIRST_INDEX];

                    $response_data["result"]["tab_id"] = $get_post["tab_id"];
                    $response_data["result"]["html"]   = $this->load->view("partials/comment_container_partial.php", array("comment_items" => [$get_post]), true);
                }

                $response_data["status"] = true;
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will fetch Posts in a Tab and generate html
        # Triggered by: (POST) posts/get
        # Requires: $tab_id
        # Returns: { status: true/false, result: { tab_id, html }, error: null }
        # Last updated at: April 17, 2023
        # Owner: Jovic, Updated by: Jovic
        public function getPosts($tab_id){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $get_posts = $this->db->query("
                    SELECT
                        posts.tab_id AS tab_id, posts.id AS post_id, users.id AS user_id, CONCAT(users.first_name, ' ', users.last_name) AS first_name, CONVERT_TZ(posts.updated_at, @@session.time_zone, '+00:00') AS date_posted,
                        posts.message, posts.cache_comments_count, users.profile_picture AS user_profile_pic,
                        (CASE WHEN posts.created_at != posts.updated_at THEN 1 ELSE 0 END) AS is_edited
                    FROM posts
                    INNER JOIN users ON users.id = posts.user_id
                    WHERE posts.tab_id = ?;", $tab_id
                );

                if($get_posts->num_rows()){
                    $response_data["result"]["tab_id"] = $tab_id;
                    $response_data["result"]["html"]   = $this->load->view("partials/comment_container_partial.php", array("comment_items" => $get_posts->result_array()), true);
                }

                $response_data["status"] = true;
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will create Post record and call getPost() to fetch Post record and generate html
        # Triggered by: (POST) posts/add
        # Requires: $params { tab_id, post_comment }, $_SESSION["user_id"]
        # Returns: { status: true/false, result: { tab_id, html }, error: null }
        # Last updated at: April 3, 2023
        # Owner: Jovic
        public function addPost($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $this->db->trans_start();
                $create_post = $this->db->query("INSERT INTO posts (user_id, tab_id, message, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW());", array($_SESSION["user_id"], $params["tab_id"], $params["post_comment"]));

                if($create_post){
                    $post_id = $this->db->insert_id();
                    $update_cache_posts_count = $this->db->query("UPDATE tabs SET cache_posts_count = cache_posts_count + 1 WHERE id = ?;", $params["tab_id"]);

                    if($update_cache_posts_count){
                        $get_post = $this->getPost($post_id);

                        if($get_post["status"]){
                            $response_data = $get_post;
                            $this->db->trans_complete();
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

        # DOCU: This function will update Post record and call getPost() to fetch Post record and generate html
        # Triggered by: (POST) posts/edit
        # Requires: $params { post_id, post_comment }
        # Returns: { status: true/false, result: { post_id, post_comment_id, html }, error: null }
        # Last updated at: April 12, 2023
        # Owner: Jovic, Updated by: Erick, Jovic
        public function editPostComment($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $this->db->trans_start();

                if(empty($params["comment_id"])){
                    $current_process = $this->db->query("UPDATE posts SET message = ?, updated_at = NOW() WHERE id = ?;", array($params["post_comment"], $params["post_id"]));
                    $record = "post";
                }
                else{
                    $current_process = $this->db->query("UPDATE comments SET message = ?, updated_at = NOW() WHERE id = ?;", array($params["post_comment"], $params["comment_id"]));
                    $record = "comment";
                }

                if($this->db->affected_rows()){
                    $current_process = ($record == "post") ? $this->getPost($params["post_id"]) : $this->getComments($params["comment_id"]);

                    if($current_process["status"]){
                        $response_data = $current_process;
                        
                        if($record == "post"){
                            $response_data["result"]["post_id"] = $params["post_id"];
                        }
    
                        $this->db->trans_complete();
                    }
                }
                else{
                    throw new Exception("Error updating {$record}.");
                }
            }
            catch (Exception $e) {
                $this->db->trans_rollback();
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will update remove Post/Comment Record and update cache_posts_count/cache_comments_count
        # Triggered by: (POST) posts/remove
        # Requires: $params { parent_id }, $_SESSION["user_id"]
        # Optionals: $params { post_id, comment_id }
        # Returns: { status: true/false, result: {}, error: null }
        # Last updated at: April 3, 2023
        # Owner: Jovic
        public function removePostComment($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $this->db->trans_start();

                # Set query values for deleting record
                $db_table        = "posts";
                $db_table_id     = $params["post_id"];
                $db_update_table = "tabs";
                $db_cache_column = "cache_posts_count";
                
                if($params["comment_id"]){
                    $db_table        = "comments";
                    $db_table_id     = $params["comment_id"];
                    $db_update_table = "posts";
                    $db_cache_column = "cache_comments_count";
                }

                # Delete all comments if a post is being deleted
                if($db_table == "posts"){
                    $this->db->query("DELETE FROM comments WHERE post_id = ?;", $params["post_id"]);
                }

                $delete_record = $this->db->query("DELETE FROM {$db_table} WHERE id = ? AND user_id = ?;", array($db_table_id, $_SESSION["user_id"]));

                if($delete_record){
                    # Update cache_posts_count/cache_comments_count
                    $update_record = $this->db->query("UPDATE {$db_update_table} SET {$db_cache_column} = {$db_cache_column} - 1 WHERE id = ?;", $params["parent_id"]);

                    if($update_record){
                        $response_data["status"] = true;
                        $response_data["result"]["delete_type"] = $db_table;

                        $this->db->trans_complete();
                    }
                }
            }
            catch (Exception $e) {
                $this->db->trans_rollback();
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will fetch Comments and generate html
        # Triggered by: (POST) modules/get_commments
        # Requires: $comment_id
        # Returns: { status: true/false, result: { post_comment_id, html }, error: null }
        # Last updated at: April 17, 2023
        # Owner: Erick, Updated by: Jovic
        public function getComments($comment_id, $post_id=NULL){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $where_statement = ($post_id) ? "posts.id =?" : "comments.id = ?";
                $where_value     = ($post_id) ? $post_id : $comment_id;

                $comments = $this->db->query("
                    SELECT
                        comments.id AS comment_id, comments.post_id AS post_comment_id, users.id AS user_id, CONCAT(users.first_name, ' ', users.last_name) AS commenter_first_name, CONVERT_TZ(comments.updated_at, @@session.time_zone, '+00:00') AS date_commented,
                        comments.message AS commenter_message, posts.cache_comments_count, users.profile_picture AS commenter_profile_pic,
                        (CASE WHEN comments.created_at != comments.updated_at THEN 1 ELSE 0 END) AS is_edited
                    FROM comments
                    INNER JOIN users ON users.id = comments.user_id
                    INNER JOIN posts ON posts.id = comments.post_id
                    WHERE $where_statement
                    ORDER BY comments.id ASC", 
                    $where_value
                );

                if($comments->num_rows()){
                    $comments = $comments->result_array();

                    $response_data["result"]["post_comment_id"] = $comments[FIRST_INDEX]["post_comment_id"];
                    $response_data["result"]["comment_id"] = $comments[FIRST_INDEX]["comment_id"];
                    $response_data["result"]["html"] = $this->load->view("partials/comment_container_partial.php", array("comment_items" => $comments), true);
                }

                $response_data["status"] = true;
            }
            catch (Exception $e) {
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }

        # DOCU: This function will create Comment record 
        # Triggered by: (POST) modules/add_comment
        # Requires: $params { post_id, post_comment }, $_SESSION["user_id"]
        # Returns: { status: true/false, result: { post_comment_id, html }, error: null }
        # Last updated at: March 20, 2023
        # Owner: Erick
        public function addComment($params){
            $response_data = array("status" => false, "result" => array(), "error" => null);

            try {
                $this->db->trans_start();
                $create_comment = $this->db->query("INSERT INTO comments (user_id, post_id, message, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW());", array($_SESSION["user_id"], $params["post_id"], $params["post_comment"]));
               
                if($create_comment){
                    $comment_id = $this->db->insert_id();
                    $update_cache_comments_count = $this->db->query("UPDATE posts SET cache_comments_count = cache_comments_count + 1 WHERE id = ?;", $params["post_id"]);

                    if($update_cache_comments_count){
                        $this->db->trans_complete();
                        
                        $response_data = $this->getComments($comment_id);
                    }
                }
            }
            catch (Exception $e) {
                $this->db->trans_rollback();
                $response_data["error"] = $e->getMessage();
            }

            return $response_data;
        }
    }
?>