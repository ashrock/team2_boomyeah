<?php
    define("BASE_FILE_URL", explode("views", $_SERVER["REQUEST_URI"])[0]);
    
    function add_file($file_url){
        echo BASE_FILE_URL . $file_url;
    }