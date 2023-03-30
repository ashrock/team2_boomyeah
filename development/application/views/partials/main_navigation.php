<?php
    $request_uri_array = explode("/", $_SERVER["REQUEST_URI"]);
    $request_uri = $request_uri_array[count($request_uri_array) - 1];
?>
<div id="nav_documentation">
    <div class="nav_holder">
        <?php 
            if( ($_SESSION["user_level_id"] == USER_LEVEL["ADMIN"] && !stristr($_SERVER["REQUEST_URI"], "docs/edit")) 
            ||  ($_SESSION["user_level_id"] != USER_LEVEL["ADMIN"] && $request_uri != "docs")){ ?>
            <a href="#" data-target="slide-out" class="sidenav-trigger"></a>
        <?php } ?>
    </div>
    <div id="slide-out" class="sidenav">
        <a href="<?= $_SESSION["user_level_id"] == USER_LEVEL["ADMIN"] ? "/docs/edit" : "/docs" ?>">
            <img src="https://village88.s3.us-east-1.amazonaws.com/boomyeah_v2/global_logo.svg" class="global_logo" alt="global_logo">
        </a>
        <ul>
        <?php if(isset($all_documentations)){ 
                foreach($all_documentations as $documentation) {?>
                <li><a href="/docs/<?= 461 ?>" class="private"><?= $documentation["title"] ?></a></li>
        <?php   }
            } else if(isset($all_sections)){ 
                $documentation = (Object) array("id" => 461);
                foreach($all_sections as $section) {?>
                <li><a href="/docs/<?= $documentation->id ?>/<?= 75 ?>"><?= $section["title"] ?></a></li>
        <?php   }
            } ?>
        </ul>
    </div>
</div>
<div id="header_container">
    <div class="header">
        <a href="<?= $_SESSION["user_level_id"] == USER_LEVEL["ADMIN"] ? "/docs/edit" : "/docs" ?>">
            <img src="https://village88.s3.us-east-1.amazonaws.com/boomyeah_v2/global_logo.svg" class="global_logo" alt="global_logo">
        </a>
        <div class="user_profile">
            <div class="user_settings">
                <img src="<?= $_SESSION["user_profile_pic"]; ?>" alt="user_profile" id="user_profile_pic" referrerpolicy="no-referrer">
                <a href="#" class="dropdown-action" id="logout" data-target="user_dropdown">logout</a>
                <ul id="user_dropdown" class="dropdown-content">
                  <li><a href="<?= base_url(); ?>logout" class="log_out_btn">Log out</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div id="mobile_container">
    <div class="header_mobile">
        <?php if(!in_array($request_uri, ["admin_documentation.php", "user_documentation.php"])){ ?>
            <a href="#" data-target="mobile_nav" class="mobile sidenav-trigger"></a>
        <?php } ?>
        <div class="row_placeholder"></div>
        <div class="user_settings">
            <img src="<?= $_SESSION["user_profile_pic"]; ?>" alt="user_profile" class="user_profile" id="user_profile_pic" referrerpolicy="no-referrer">
            <a href="#" class="dropdown-action" id="mobile_logout" data-target="mobile_user_dropdown">logout</a>
            <ul id="mobile_user_dropdown" class="dropdown-content">
              <li><a href="<?= base_url(); ?>logout" class="log_out_btn">Log out</a></li>
            </ul>
        </div>
    </div>
    <div id="mobile_nav" class="sidenav">
        <a href="<?= $_SESSION["user_level_id"] == USER_LEVEL["ADMIN"] ? "/docs/edit" : "/docs" ?>">
            <img src="https://village88.s3.us-east-1.amazonaws.com/boomyeah_v2/global_logo.svg" class="global_logo" alt="global_logo">
        </a>
        <ul>
        <?php if(isset($all_documentations)){ 
                foreach($all_documentations as $documentation) {?>
                <li><a href="/docs/<?= 461 ?>" class="private"><?= $documentation["title"] ?></a></li>
        <?php   }
            } else if(isset($all_sections)){ 
                $documentation = (Object) array("id" => 461);
                foreach($all_sections as $section) {?>
                <li><a href="/docs/<?= $documentation->id ?>/<?= 75 ?>"><?= $section["title"] ?></a></li>
        <?php   }
            } ?>
        </ul>
    </div>
</div>
