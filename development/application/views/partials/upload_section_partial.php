
<a href="#!" class="fetch_files_btn">Files <span id="files_counter" data-files_count=<?= count($fetch_uploaded_files_data) ?>>(<?= count($fetch_uploaded_files_data)?>)</span></a>
<div class="files_upload_content">
    <form id="upload_files_form" action="/files/upload" method="POST" enctype="multipart/form-data">
        <input type="file" id="file_upload_contents" name="upload_file[]" hidden multiple>
        <button id="file_upload_btn" class="" type="button">Upload Files</button>
        <input type="hidden" name="section_id" value="<?= $section_id ?>">
        <label for="file_upload_contents">Max size for each file: 25mb</label>
    </form>
    <ul id="files_list">
        <?php
            $file_items_array = array("fetch_uploaded_files_data" => $fetch_uploaded_files_data);
            $this->load->view("partials/upload_section_items_partial.php", $file_items_array);
        ?>
    </ul>
</div>