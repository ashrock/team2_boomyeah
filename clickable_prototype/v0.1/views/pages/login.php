<?php include_once("../view_helper.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BoomYEAH Login</title>
    <link rel="shortcut icon" href="<?= add_file("assets/images/favicon.ico") ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= add_file("assets/css/login.css") ?>">
</head>
<body>
    <div id="login_wrapper">
        <div class="login_side_container">
            <div class="documentation_wrapper">
                <div class="login_documentation">
                    <h1>Boom<span>YEAH</span></h1>
                    <p>Your Team's Documentation Tool</p>
                    <a href="<?= VIEWS_URL ?>/pages/admin_documentation.php" class="login_btn">Log In with Google</a>
                </div>
            </div>
        </div>
        <img src="<?= add_file("assets/images/login_illustration.png") ?>" alt="login_illustration">
    </div>
</body>
</html>