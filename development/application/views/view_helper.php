<?php
    define("BASE_FILE_URL", explode("views", $_SERVER["REQUEST_URI"])[0]);
    define("VIEWS_URL", BASE_FILE_URL . "views");
    
    /**
     * DOCU: Load a file using the absolute file URI based on the `views` folder location 
     */
    function add_file($file_url){
        echo base_url($file_url);
    }

    function load_view($file_url, $variables = array()){
        extract($variables);
        include($file_url);
    }

    function get_navigation_link($side_nav_link){
        if((preg_match("/^\/docs\/[0-9]{0,6}\/edit$/i", $_SERVER["REQUEST_URI"]) || (preg_match("/^\/docs\/[0-9]{0,6}$/i", $_SERVER["REQUEST_URI"])))){
            $navigation_link = $_SESSION["user_level_id"] == USER_LEVEL["ADMIN"] ? base_url() . "docs/{$side_nav_link["id"]}/edit" : base_url() . "docs/{$side_nav_link["id"]}";
        }
        else if((preg_match("/^\/docs\/[0-9]{0,6}\/[0-9]{0,6}\/edit$/i", $_SERVER["REQUEST_URI"]) || (preg_match("/^\/docs\/[0-9]{0,6}\/[0-9]{0,6}$/i", $_SERVER["REQUEST_URI"])))){
            $navigation_link = $_SESSION["user_level_id"] == USER_LEVEL["ADMIN"] ? base_url() . "docs/{$side_nav_link["documentation_id"]}/{$side_nav_link["id"]}/edit" : base_url() . "docs/{$side_nav_link["documentation_id"]}/{$side_nav_link["id"]}";
        }

        return $navigation_link;
    }

    function get_navigation_header(){
        if((preg_match("/^\/docs\/[0-9]{0,6}\/edit$/i", $_SERVER["REQUEST_URI"]) || (preg_match("/^\/docs\/[0-9]{0,6}$/i", $_SERVER["REQUEST_URI"])))){
            $navigation_header = "Documentations";
        }
        else if((preg_match("/^\/docs\/[0-9]{0,6}\/[0-9]{0,6}\/edit$/i", $_SERVER["REQUEST_URI"]) || (preg_match("/^\/docs\/[0-9]{0,6}\/[0-9]{0,6}$/i", $_SERVER["REQUEST_URI"])))){
            $navigation_header = "Sections";
        }

        return $navigation_header;

    }