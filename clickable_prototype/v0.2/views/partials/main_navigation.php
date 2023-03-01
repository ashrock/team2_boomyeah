<?php
    $request_uri = explode("/", $_SERVER["REQUEST_URI"]);
    $request_uri = $request_uri[count($request_uri) - 1];
?>
<div id="nav_documentation">
    <div class="nav_holder">
        <?php if(!in_array($request_uri, ["admin_documentation.php", "user_documentation.php"])){ ?>
            <a href="#" data-target="slide-out" class="sidenav-trigger"></a>
        <?php } ?>
    </div>
    <ul id="slide-out" class="sidenav">
        <a href="#" data-target="slide-out" class="sidenav-trigger nav"></a>
        <li><a href="#!">About Company</a></li>
        <li><a href="#!">Terms of Employment</a></li>
        <li><a href="#!">General Policies & Procedures</a></li>
        <li><a href="#!">Career & Personnel Develop...</a></li>
        <li><a href="#!">Important Notes</a></li>
        <li><a href="#!">Compensation & Benefits</a></li>
    </ul>
</div>
<div id="header_container">
    <div class="header">
        <a href="./admin_documentation.html"><img src="https://village88.s3.us-east-1.amazonaws.com/boomyeah_v2/global_logo.svg" class="global_logo" alt="global_logo"></a>
        <div class="user_profile">
            <div class="user_settings">
                <img src="https://village88.s3.us-east-1.amazonaws.com/boomyeah_v2/user_profile.png" alt="user_profile">
                <a href="#" class="dropdown-action" id="logout" data-target='dropdown_logout'>logout</a>
                <ul id='dropdown_logout' class='dropdown-content'>
                  <li><a href="./login.php" class="log_out_btn">Log out</a></li>
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
        <ul id="mobile_nav" class="sidenav">
            <a href="#" data-target="mobile_nav" class="sidenav-trigger"></a>
            <li><img src="<?= add_file("assets/images/global_logo.svg") ?>" alt="mobile_logo"></li>
            <li><span>Documentations</span></li>
            <li><a href="#!">Employee Handbook</a></li>
            <li><a href="#!">Marketing</a></li>
            <li><a href="#!">UI/UX</a></li>
            <li><a href="#!">Trainee</a></li>
            <li><a href="#!">Engineering</a></li>
        </ul>
        <form action="#" id="select_section_form" method="POST">
            <div class="input-field select">
                <select class='dropdown-content'>
                    <option value="employee_handbook">Employee Handbook</option>
                    <option value="about_company">About Company</option>
                    <option value="terms">Terms of Employment</option>
                    <option value="general">General Policies & Procedures</option>
                </select>
            </div>
        </form>
        <img src="https://village88.s3.us-east-1.amazonaws.com/boomyeah_v2/user_profile.png" alt="user_profile" class="user_profile" >
    </div>
</div>
