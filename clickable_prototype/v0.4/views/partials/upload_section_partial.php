
<a href="#!" class="fetch_files_btn">Files <span id="files_counter" <?= (!count($fetch_uploaded_files_data))? "hidden" : "" ?>>(<?= count($fetch_uploaded_files_data)?>)</span></a>
<div class="files_upload_content">
    <form id="upload_files_form" action="<?= BASE_FILE_URL ?>processes/manage_documentation.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="upload_a_file">
        <input type="file" id="file_upload_contents" name="upload_file[]" hidden multiple>
        <button id="file_upload_btn" class="" type="button">Upload Files</button>
        <label for="">Maximun size: 25mb</label>
    </form>
    <ul id="files_list">
        <?php
            $file_items_array = array( "fetch_uploaded_files_data" => $fetch_uploaded_files_data);
            load_view("../partials/upload_section_items_partial.php", $file_items_array);
        ?>
    </ul>
</div>
