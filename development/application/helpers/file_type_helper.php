<?php
    function getFileType($mime) {
        switch ($mime) {
            case 'image/gif':
            case 'image/jpeg':
            case 'image/png':
            case 'image/svg+xml':
                return 'img_type';
                break;
            case 'application/msword':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
            case 'application/vnd.ms-excel':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
            case 'application/pdf':
                return 'docs_type';
                break;
            default:
            return 'unknown_type';
        }
    }
?>