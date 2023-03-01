<?php
    function get_include_contents($filename, $variables = array()) {
        if (is_file($filename)) {
            extract($variables);
    
            ob_start();
            include $filename;
            return ob_get_clean();
        }
        return false;
    }
?>