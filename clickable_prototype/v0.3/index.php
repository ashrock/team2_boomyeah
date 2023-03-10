<?php
  $dir_path = "./views/pages";
  $files = scandir($dir_path);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="UX Team 2">
        <link rel="shortcut icon" href="./assets/images/favicon.ico" type="image/x-icon">
        <meta name="description" content="A great way to describe your documentation tool">
        <title>Boom Yeah | Team 2</title>
        <link rel="stylesheet" href="./assets/css/index.css"/>
    </head>
    <body>
        <div class="header">
            <img src="./assets/images/global_logo.svg" class="global_logo" alt="global_logo">
            <div class="divider"></div>
            <h1>Team <span>2</span></h1>
        </div>
        <ul>
            <?php foreach($files as $file_name) {
                // Ignore the current and parent directory entries and directories
                if ($file_name === '.' || $file_name === '..' || is_dir($dir_path . '/' . $file_name)) {
                    continue;
                }
            ?>
                <li>
                    <a href="./views/pages/<?= $file_name ?>">
                        <?= ucwords(str_replace(['.php', '_'], ['', ' '], $file_name)) ?>
                    </a>
                    <iframe src="./views/pages/<?= $file_name?>" scrolling="no" frameBorder="0" class="frame_display"></iframe>
                </li>
            <?php } ?>
        </ul>
    </body>
</html>
