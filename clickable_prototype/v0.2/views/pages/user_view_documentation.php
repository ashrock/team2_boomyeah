<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="UX Team 2">
    <meta name="description" content="A great way to describe your documentation tool">
    <title>Boom Yeah | Admin Edit Documentation Page</title>
    <link rel="shortcut icon" href="../../assets/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/css/global.css">
    <link rel="stylesheet" href="../../assets/css/user_view_documentation.css">
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="../../assets/js/vendor/html_loader.lib.js"></script>
    <script src="../../assets/js/vendor/ux.lib.js"></script>
</head>
<body>
    <!--- Add #main_navigation --->
    <div id="main_navigation"></div>
    <!--- Add #invite_modal --->
    <div id="invite_modal"></div>
    
    <div id="wrapper">
        <div class="container">
            <ul id="breadcrumb_list">
                <li class="breadcrumb_item"><a href="admin_documentation.html">Documentation</a></li>
                <li class="breadcrumb_item mobile_breadcrumb"><a href="user_documentation.html">&lt;</a></li>
                <li class="breadcrumb_item active">Employee Handbook</li>
            </ul>
            <div class="divider"></div>
            <div id="doc_title_access">
                <h1 id="doc_title">Employee Handbook</h1>
            </div>
            <p class="doc_text_content">This handbook replaces and supersedes all prior employee handbooks regarding employment or HR matters effective January 01, 2021. The policies and practices included in this handbook may be modified at any time. Your department has additional specific procedures for many of the general policies stated in the handbook. You are expected to learn your department's procedures and comply with them. You are also expected to conform to the professional standards of your occupation. Please direct any questions to your supervisor, department head, or to the Human Resources Management and Development Office.</p>
            <div class="section_header">
                <h2>Sections</h2>
                <button class="sort_btn dropdown-trigger" data-target="sort_list">Sort by</button>
                <!-- Dropdown Structure -->
                <ul id="sort_list" class="dropdown-content sort_dropdown">
                    <li><a href="#!" class="sort_by" data-sort-by="az">A-Z</a></li>
                    <li><a href="#!" class="sort_by" data-sort-by="za">Z-A</a></li>
                </ul>
            </div>
            <div class="section_container" id="section_container">
                <div class="section_block">
                    <div class="section_details">
                        <input type="text" name="section_title" value="About Company" id="" class="section_title tooltipped" data-tooltip="About Company">
                    </div>
                    <div class="section_controls">
                        <span>4 Tabs</span>
                    </div>
                </div>
                <div class="section_block">
                    <div class="section_details">
                        <input type="text" name="section_title" value="Terms of Employment" id="" class="section_title tooltipped" data-tooltip="Terms of Employment">
                    </div>
                    <div class="section_controls">
                        <span>17 Tabs</span>
                    </div>
                </div>
                <div class="section_block">
                    <div class="section_details">
                        <input type="text" name="section_title" value="General Policies & Procedures" id="" class="section_title tooltipped" data-tooltip="General Policies & Procedures">
                    </div>
                    <div class="section_controls">
                        <span>18 Tabs</span>
                    </div>
                </div>
                <div class="section_block">
                    <div class="section_details">
                        <input type="text" name="section_title" value="Compensation & Benefits" id="" class="section_title tooltipped" data-tooltip="Compensation & Benefits">
                    </div>
                    <div class="section_controls">
                        <span>9 Tabs</span>
                    </div>
                </div>
                <div class="section_block">
                    <div class="section_details">
                        <input type="text" name="section_title" value="Career & Personnel Development" id="" class="section_title tooltipped" data-tooltip="Career & Personnel Development">
                    </div>
                    <div class="section_controls">
                        <span>13 Tabs</span>
                    </div>
                </div>
                <div class="section_block">
                    <div class="section_details">
                        <input type="text" name="section_title" value="Important Notes" id="" class="section_title tooltipped" data-tooltip="Important Notes">
                    </div>
                    <div class="section_controls">
                        <span>19 Tabs</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--JavaScript at end of body for optimized loading-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script type="module" src="../../assets/js/hotkeys.js"></script>
    <script type="module" src="../../assets/js/user_view_documentation.js"></script>
</body>
</html> 