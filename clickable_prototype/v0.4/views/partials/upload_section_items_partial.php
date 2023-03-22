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

    $scheme = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http';
    $link = $scheme . "://" . $_SERVER['HTTP_HOST'] . "/clickable_prototype/v0.4/assets/json/uploaded_files/";
?>

<?php if(count($fetch_uploaded_files_data)) { 
        foreach($fetch_uploaded_files_data as $uploaded_file){ ?>
            <li class="file_<?= $uploaded_file["file_id"] ?>">
                <a href="<?= $link . $uploaded_file["file_name"]?>" download class="file_type <?= getFileType($uploaded_file["file_type"])?>"><?= $uploaded_file["file_name"]?></a>
                <ul class="actions_list">
                    <li>
                        <input type="hidden" value="<?= $link . $uploaded_file["file_name"]?>">
                        <a href="#" class="copy_link_icon"></a>
                        <span class="tooltip_hover">Copy Link</span>
                    </li>
                    <li>
                        <a href="#" class="delete_icon" data-file_id="<?= $uploaded_file["file_id"]?>" data-file_name="<?= $uploaded_file["file_name"]?>" data-file_is_used="<?= $uploaded_file["is_used"]?>"></a>
                        <span class="tooltip_hover">Delete</span>
                    </li>
                </ul>
            </li>
        <?php } ?>
<?php } else { ?>
    <li>
        <p class="no_uploaded_files">You have no uploaded files...</p>
    </li>
<?php } ?>