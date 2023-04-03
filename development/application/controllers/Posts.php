<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Posts extends CI_Controller {
		public function __construct(){
			parent::__construct();

			$this->load->model("Post");
		}

		# DOCU: This function will call getPosts() from Post Model to process fetching of Tab Posts
		# Triggered by: (POST) posts/get
		# Requires: $_POST["tab_ib"]
		# Returns: { status: true/false, result: { tab_id, html }, error: null }
		# Last updated at: April 3, 2023
		# Owner: Jovic
		public function getPosts(){
			$response_data = array("status" => false, "result" => array(), "error" => null);

			try {
				$response_data = $this->Post->getPosts($_POST["tab_id"]);
			}
			catch (Exception $e) {
				$response_data["error"] = $e->getMessage();
			}

			echo json_encode($response_data);
		}

        # DOCU: This function will call addPost() from Post Model to process creating of Tab record
		# Triggered by: (POST) posts/add
		# Requires: $_POST["tab_id", "post_comment"]
		# Returns: { status: true/false, result: { tab_id, html }, error: null }
		# Last updated at: April 3, 2023
		# Owner: Jovic
		public function addPost(){
			$response_data = array("status" => false, "result" => array(), "error" => null);

			try {
				$response_data = $this->Post->addPost($_POST);
			}
			catch (Exception $e) {
				$response_data["error"] = $e->getMessage();
			}

			echo json_encode($response_data);
		}

        # DOCU: This function will call editPost() / editComment() from Module Model to process updating of post/comment
		# Triggered by: (POST) modules/update_post
		# Requires: $_POST["post_id", "comment_id", "post_comment"]
		# Returns: { status: true/false, result: { post_id, post_comment_id, html }, error: null }
		# Last updated at: Mar. 21, 2023
		# Owner: Jovic, Updated by: Erick
		public function editPostComment(){
			$response_data = array("status" => false, "result" => array(), "error" => null);

			try {
				$response_data =  $this->Post->editPostComment($_POST);
			}
			catch (Exception $e) {
				$response_data["error"] = $e->getMessage();
			}

			echo json_encode($response_data);
		}

        # DOCU: This function will call removePost() from Module Model to process removing of Post or Comment record
		# Triggered by: (POST) posts/remove
		# Requires: $_POST["parent_id"]
		# Optionals: $_POST["post_id", "comment_id"]
		# Returns: { status: true/false, result: { post_id, post_comment_id, html }, error: null }
		# Last updated at: April 3, 2023
		# Owner: Jovic
		public function removePostComment(){
			$response_data = array("status" => false, "result" => array(), "error" => null);

			try {
				$response_data = $this->Post->removePostComment($_POST);
			}
			catch (Exception $e) {
				$response_data["error"] = $e->getMessage();
			}

			echo json_encode($response_data);
		}
	}
?>