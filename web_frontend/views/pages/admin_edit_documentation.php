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
    <link rel="stylesheet" href="../../assets/css/admin_edit_documentation.css">
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="../../assets/css/vendor/animate.min.css">
    <script src="../../assets/js/vendor/html_loader.lib.js"></script>
    <script src="../../assets/js/vendor/ux.lib.js"></script>
    <script src="../../assets/js/vendor/Sortable.min.js"></script>
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
                <li class="breadcrumb_item active">Employee Handbook</li>
            </ul>
            <div class="divider"></div>
            <div id="doc_title_access">
                <h1 id="doc_title">Employee Handbook</h1>
                <!-- Switch -->     
                <div class="switch switch_btn">
                    <label>
                        <span class="toggle_text">Public</span>
                        <input class="toggle_switch" type="checkbox">
                        <span class="lever"></span>
                    </label>
                </div>
                <a id="invite_collaborator_btn" class="waves-effect waves-light btn modal-trigger hidden" href="#modal1">13 Collaborators</a>
            </div>
            <p class="doc_text_content" contenteditable="true" data-placeholder="Add Description"></p>
            <form action="#" id="section_form" method="post">
                <div class="group_add_section input-field">
                    <input id="input_add_section" type="text" class="validate" autofocus>
                    <label for="input_add_section">Add Section</label>
                </div>
            </form>
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
                <div id="section_1" class="section_block hidden">
                    <div class="section_details">
                        <input type="text" name="section_title" value="About Company" id="" class="section_title" readonly="">
                    </div>
                    <div class="section_controls">
                        <button class="more_action_btn dropdown-trigger" data-target="section_more_actions_1">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="section_more_actions_1" class="dropdown-content more_action_list">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="1" data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="section_2" class="section_block">
                    <div class="section_details">
                        <input type="text" name="section_title" value="About Company" id="" class="section_title" readonly="">
                    </div>
                    <div class="section_controls">
                        <button class="more_action_btn dropdown-trigger" data-target="section_more_actions_2">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="section_more_actions_2" class="dropdown-content more_action_list">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="2"
                                    data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="section_3" class="section_block">
                    <div class="section_details">
                        <input type="text" name="section_title" value="Terms of Employment" id="" class="section_title" readonly="">
                    </div>
                    <div class="section_controls">
                        <button class="more_action_btn dropdown-trigger" data-target="section_more_actions_3">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="section_more_actions_3" class="dropdown-content more_action_list">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="3"
                                    data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="section_4" class="section_block">
                    <div class="section_details">
                        <input type="text" name="section_title" value="General Policies and Procedures" id="" class="section_title" readonly="">
                
                    </div>
                    <div class="section_controls">
                        <button class="more_action_btn dropdown-trigger" data-target="section_more_actions_4">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="section_more_actions_4" class="dropdown-content more_action_list">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="4"
                                    data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="section_5" class="section_block">
                    <div class="section_details">
                        <input type="text" name="section_title" value="Compensation & Benefits" id="" class="section_title" readonly="">
                    </div>
                    <div class="section_controls">
                        <button class="more_action_btn dropdown-trigger" data-target="section_more_actions_5">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="section_more_actions_5" class="dropdown-content more_action_list_public">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="5"
                                    data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="section_6" class="section_block">
                    <div class="section_details">
                        <input type="text" name="section_title" value="Career & Personnel Development" id="" class="section_title" readonly="">
                    </div>
                    <div class="section_controls">
                        <button class="more_action_btn dropdown-trigger" data-target="section_more_actions_6">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="section_more_actions_6" class="dropdown-content more_action_list_private">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="6"
                                    data-documentation_action="remove">Remove</a></li>
                    </div>
                </div>
                <div id="section_7" class="section_block">
                    <div class="section_details">
                        <input type="text" name="section_title" value="Important Notes" id="" class="section_title" readonly="">
                    </div>
                    <div class="section_controls">
                        <button class="more_action_btn dropdown-trigger" data-target="section_more_actions_7">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="section_more_actions_7" class="dropdown-content more_action_list_private">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="7"
                                    data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="section_8" class="section_block">
                    <div class="section_details">
                        <input type="text" name="section_title" value="Village 88 Story" id="" class="section_title" readonly="">
                    </div>
                    <div class="section_controls">
                        <button class="more_action_btn dropdown-trigger" data-target="section_more_actions_8">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="section_more_actions_8" class="dropdown-content more_action_list_private">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="8"
                                    data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="section_9" class="section_block">
                    <div class="section_details">
                        <input type="text" name="section_title" value="457 Ave Documents" id="" class="section_title" readonly="">
                    </div>
                    <div class="section_controls">
                        <button class="more_action_btn dropdown-trigger" data-target="section_more_actions_9">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="section_more_actions_9" class="dropdown-content more_action_list_private">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="9"
                                    data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="section_10" class="section_block">
                    <div class="section_details">
                        <input type="text" name="section_title" value="V88 Liga" id="" class="section_title" readonly="">
                    </div>
                    <div class="section_controls">
                        <button class="more_action_btn dropdown-trigger" data-target="section_more_actions_10">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="section_more_actions_10" class="dropdown-content more_action_list_public">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="10"
                                    data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="section_11" class="section_block">
                    <div class="section_details">
                        <input type="text" name="section_title" value="Team Na-Ghost" id="" class="section_title" readonly="">
                    </div>
                    <div class="section_controls">
                        <button class="more_action_btn dropdown-trigger" data-target="section_more_actions_11">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="section_more_actions_11" class="dropdown-content more_action_list_public">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="11"
                                    data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="section_12" class="section_block">
                    <div class="section_details">
                        <input type="text" name="section_title" value="Team Galas" id="" class="section_title" readonly="">
                    </div>
                    <div class="section_controls">
                        <button class="more_action_btn dropdown-trigger" data-target="section_more_actions_12">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="section_more_actions_12" class="dropdown-content more_action_list_private">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="12"
                                    data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="section_13" class="section_block">
                    <div class="section_details">
                        <input type="text" name="section_title" value="V88 Builders" id="" class="section_title" readonly="">
                    </div>
                    <div class="section_controls">
                        <button class="more_action_btn dropdown-trigger" data-target="section_more_actions_13">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="section_more_actions_13" class="dropdown-content more_action_list_public">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="13"
                                    data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="no_sections hidden">
                <img src="https://village88.s3.us-east-1.amazonaws.com/boomyeah_v2/empty_illustration.png"
                    alt="Empty Content Illustration">
                <p>You have no sections yet</p>
            </div>
        </div>
    </div>
    <div id="confirmation_modal_remove_invited_user">
        <div id="confirm_to_remove_invited_user" class="modal">
            <div class="modal-content">
                <h4>Confirmation</h4>
                <p>Are you sure you want to remove access for this user?</p>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect btn-flat no_btn">No</a>
                <a href="#!" id="remove_invited_user_confirm" class="modal-close waves-effect btn-flat yes_btn">Yes</a>
            </div>
        </div>
    </div>
    <div id="confirmation_modal_remove">
        <div id="confirm_to_remove" class="modal">
            <div class="modal-content">
                <h4>Confirmation</h4>
                <p>Are you sure you want to remove this section?</p>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect btn-flat no_btn">No</a>
                <a href="#!" id="remove_confirm" class="modal-close waves-effect btn-flat yes_btn">Yes</a>
            </div>
        </div>
    </div>
    <form id="remove_section_form" action="#" method="POST" hidden>
        <input type="hidden" id="remove_section_id" name="section_id">
    </form>
    <form action="remove_invited_user_form" action="#" method="POST" hidden>
        <input type="hidden" id="invited_user_id" name="invited_user_id">
    </form>
    <!--JavaScript at end of body for optimized loading-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script type="module" src="../../assets/js/admin_edit_documentation.js"></script>
    <script type="text/javascript" src="../../assets/js/invite_modal.js"></script>
    <script type="text/javascript" src="../../assets/js/hotkeys.js"></script>
</body>
</html> 