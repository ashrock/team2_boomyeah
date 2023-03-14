<?php
    // is_archived, and is_private
    $_NO  = 0;
    $_YES = 1;

    $_ZERO_VALUE = 0;

    $_USER_LEVEL = array("admin" => 9, "user" => 1);

    define("BASE_FILE_URL", explode("views", $_SERVER["REQUEST_URI"])[0]);
    define("VIEWS_URL", BASE_FILE_URL . "views");
?>