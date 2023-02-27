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