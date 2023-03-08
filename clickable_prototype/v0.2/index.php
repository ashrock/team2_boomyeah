<?php
  $dir_path = "./views/pages";
  $files = scandir($dir_path);
?>
<link rel="stylesheet" href="./assets/less/index.less"/>
<ul>
    <?php foreach($files as $file_name) {
        // Ignore the current and parent directory entries and directories
        if ($file_name === '.' || $file_name === '..' || is_dir($dir_path . '/' . $file_name)) {
            continue;
        }
    ?>
        <li>
            <a href="./views/pages/<?= str_replace(['.php', '_'], ['', ' '], $file_name) ?>">
                <?= ucwords(str_replace(['.php', '_'], ['', ' '], $file_name)) ?>
            </a>
            <iframe src="./views/pages/<?= $file_name?>" scrolling="no" frameBorder="0" class="frame_display"></iframe>
        </li>
    <?php } ?>
</ul>
