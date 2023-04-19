<?php
    $page_navigation_data = null;

    if(isset($document_data) || isset($section)) {
        $documentation_id = isset($document_data) ? $document_data["id"] : $section["documentation_id"];
        $page_navigation_data = isset($document_data) ? $document_data : $section;
    }
?>
<div id="nav_documentation">
    <div class="nav_holder">
        <?php if(!in_array($_SERVER["REQUEST_URI"], ["/docs/edit", "/docs"])){ ?>
            <a href="#" data-target="slide-out" class="sidenav-trigger"></a>
        <?php } ?>
    </div>

    <div id="slide-out" class="sidenav">
        <a class="sidenav_logo" href="<?= $_SESSION["user_level_id"] == USER_LEVEL["ADMIN"] ? "/docs/edit" : "/docs" ?>">
            <img src="https://village88.s3.us-east-1.amazonaws.com/boomyeah_v2/global_logo.svg" class="global_logo" alt="global_logo">
        </a>
        <h3><?= get_navigation_header() ?></h3>
        <ul>
    <?php foreach($side_nav_links as $side_nav_link){
        $link = get_navigation_link($side_nav_link);
    ?>
        <li class="<?= ($side_nav_link["id"] == $page_navigation_data["id"]) ? 'active' : '' ?> <?= (strlen($side_nav_link["title"]) >= 30) ? 'tooltipped' : '' ?>" data-tooltip="<?= htmlspecialchars($side_nav_link["title"]) ?>"><a href="<?= $link ?>" class="<?= (isset($side_nav_link["is_private"]) && $side_nav_link["is_private"]) ? 'private' : '' ?>"><?= htmlspecialchars($side_nav_link["title"]) ?></a><span></span></li>
    <?php } ?>
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
        <?php if(!in_array($_SERVER["REQUEST_URI"], ["admin_documentation.php", "user_documentation.php"])){ ?>
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
        <a class="sidenav_logo" href="<?= $_SESSION["user_level_id"] == USER_LEVEL["ADMIN"] ? "/docs/edit" : "/docs" ?>">
            <img src="https://village88.s3.us-east-1.amazonaws.com/boomyeah_v2/global_logo.svg" class="global_logo" alt="global_logo">
        </a>
        <div id="mobile_nav_title"><?= (isset($view_page)) ? $view_page : '' ?></div>
        <ul>
        <?php foreach($side_nav_links as $side_nav_link){ 
            $link = get_navigation_link($side_nav_link);
        ?>
            <li class="<?= ($side_nav_link["id"] == $page_navigation_data["id"]) ? 'active' : '' ?>"><a href="<?= $link ?>" class="<?= (isset($side_nav_link["is_private"]) && $side_nav_link["is_private"]) ? 'private' : '' ?>"><?= htmlspecialchars($side_nav_link["title"]) ?></a><span></span></li>
        <?php } ?>  
        </ul>
    </div>
</div>
