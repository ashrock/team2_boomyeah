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

    function load_json_file($filepath) {
        $data = [];
        if (file_exists($filepath)) {
            $json_data = file_get_contents($filepath);
            $data = json_decode($json_data, true);
        }
        return $data;
    }
?>