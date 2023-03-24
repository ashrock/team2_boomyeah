<?php
    $this->load->helper("file_type");

    if(count($fetch_uploaded_files_data)) { 
        foreach($fetch_uploaded_files_data as $uploaded_file){ ?>
            <li class="file_<?= $uploaded_file["file_id"] ?>">
                <a href="<?= $uploaded_file["file_url"]?>" download class="file_type <?= getFileType($uploaded_file["mime_type"]) ?>"><?= $uploaded_file["file_name"]?></a>
                <ul class="actions_list">
                    <li>
                        <input type="text" readonly class="file_link" value="<?= "https://boomyeah-docs-2.s3.amazonaws.com/{$uploaded_file["file_url"]}"?>">
                        <a href="#" class="copy_link_icon"></a>
                        <span class="tooltip_hover">Copy Link</span>
                    </li>
                    <li>
                        <a href="#" class="delete_icon" data-section_id="<?= $uploaded_file["section_id"] ?>" data-file_id="<?= $uploaded_file["file_id"]?>" data-file_name="<?= $uploaded_file["file_name"]?>" data-file_url="<?= $uploaded_file["file_url"]?>" data-file_is_used="<?php # $uploaded_file["is_used"] ?>"></a>
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