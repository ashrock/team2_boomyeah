<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="UX Team 2">
    <meta name="description" content="A great way to describe your documentation tool">
    <title>Boom Yeah</title>
    <link rel="shortcut icon" href="../../assets/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <link rel="stylesheet" href="../../assets/css/global.css">
    <script src="../../assets/js/nav.js"></script>
</head>

<body>
    <div id="header_container">
        <div class="header">
            <img src="../../assets/images/global_logo.svg" class="global_logo" alt="global_logo">
            <div class="user_profile">
                <a href="#" class="switch_to_user">Switch to User</a>
                <div class="user_settings">
                    <img src="../../assets/images/user_profile.png" alt="user_profile">
                    <button></button>
                </div>
            </div>
        </div>
        <div id="nav_documentation">
            <div class="nav_holder">
                <a href="#" data-target="slide-out" class="sidenav-trigger"></a>
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
    </div>
    <div id="mobile_container">
        <div class="header_mobile">
            <a href="#" data-target="mobile_nav" class="mobile sidenav-trigger"></a>
            <ul id="mobile_nav" class="sidenav">
                <a href="#" data-target="slide-out" class="sidenav-trigger"></a>
                <li><img src="../../assets/images/global_logo.svg" alt="mobile_logo"></li>
                <li><span>Documentations</span></li>
                <li><a href="#!">Employee Handbook</a></li>
                <li><a href="#!">Marketing</a></li>
                <li><a href="#!">UI/UX</a></li>
                <li><a href="#!">Trainee</a></li>
                <li><a href="#!">Engineering</a></li>
            </ul>
            <form action="#" method="GET">
                <div class="input-field select">
                    <select>
                      <option value="employee_handbook">Employee Handbook</option>
                      <option value="employee_handbook">About Company</option>
                      <option value="terms">Terms of Employment</option>
                      <option value="general">General Policies & Procedures</option>
                    </select>
                </div>
            </form>
            <img src="../../assets/images/user_profile.png" alt="user_profile">
        </div>
    </div>
</body>

</html>