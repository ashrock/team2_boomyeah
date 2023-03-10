<?php

if(is_ajax_request()){
    $json_response = $_POST;
    
    if(array_key_exists("documentation", $_POST)){
        $documentation_data = array(
            ...$json_response["documentation"],
            "id" => time(),
            "is_archived" => FALSE,
            "is_private" => FALSE,
            "cache_collaborators_count" => 10
        );

        $html = get_include_contents("./partials/document_block_partial.php", $documentation_data);
        $json_response["html"] = $html;
    }

    echo json_encode($json_response);
}

function is_ajax_request()
{
    return (strtolower($_SERVER['REQUEST_METHOD']) == 'post' || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'));
}

function get_include_contents($filename, $variables = array()) {
    if (is_file($filename)) {
        extract($variables);

        ob_start();
        include $filename;
        return ob_get_clean();
    }
    return false;
}