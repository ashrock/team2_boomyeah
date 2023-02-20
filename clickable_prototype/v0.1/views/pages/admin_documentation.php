<?php include_once("../view_helper.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="UX Team 2">
    <meta name="description" content="A great way to describe your documentation tool">
    <title>Boom Yeah | Admin Documentation Page</title>
    <link rel="shortcut icon" href="<?= add_file("assets/images/favicon.ico") ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= add_file("assets/css/global.css") ?>">
    <link rel="stylesheet" href="<?= add_file("assets/css/admin_documentation.css") ?>">
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="<?= add_file("assets/js/vendor/jquery-3.6.3.min.js") ?>"></script>
    <script src="<?= add_file("assets/js/vendor/Sortable.min.js") ?>"></script>
</head>

<body>
    <!--- Add #main_navigation --->
    <div id="main_navigation"><?= include_once("../partials/main_navigation.php") ?></div>
    <!--- Add #invite_modal --->
    <div id="invite_modal"><?= include_once("../partials/invite_modal.php") ?></div>
    <div id="wrapper">
        <div class="container">
            <form action="./admin_documentation.html" id="doc_form" method="post">
                <div class="group_add_documentation input-field">
                    <input id="input_add_documentation" type="text" class="validate" autofocus>
                    <label for="input_add_documentation">Add Documentation</label>
                </div>
                <span id="save_status" hidden>Saving...</span>
            </form>
            <div class="section_header">
                <button id="docs_view_btn" class="dropdown-trigger" data-target="docs_view_list">Documentations</button>
                <ul id='docs_view_list' class='dropdown-content'>
                    <li><a href="#!" class="active_docs_btn">Documentations</a></li>
                    <li class="divider" tabindex="-1"></li>
                    <li><a href="#!" class="archived_docs_btn">Archived</a></li>
                </ul>
                <button id="sort_by_btn" class="dropdown-trigger sort_btn" data-target="sort_by_list">Sort by</button>
                <ul id='sort_by_list' class='dropdown-content sort_dropdown'>
                    <li class="sort_by" data-sort-by="az">A-Z</li>
                    <li class="sort_by" data-sort-by="za">Z-A</li>
                </ul>
            </div>
            <div id="documentations">
                <div id="document_1" class="document_block">
                    <div class="document_details">
                        <input type="text" name="document_title" value="Employee Handbook" id="" class="document_title" readonly="">
                        <button class="invite_collaborators_btn modal-trigger" href="#modal1"> 15</button>
                    </div>
                    <div class="document_controls">
                        <button class="access_btn modal-trigger set_privacy_btn" href="#confirm_to_public" data-document_id="1" data-document_privacy="private"></button>
                        <button class="more_action_btn dropdown-trigger" data-target="document_more_actions_1">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="document_more_actions_1" class="dropdown-content more_action_list_private">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_archive" class="archive_icon modal-trigger archive_btn" data-document_id="1" data-documentation_action="archive">Archive</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#modal1" class="invite_icon modal-trigger">Invite</a></li>
                            <li class="divider" tabindex="-1"></li><li><a href="#confirm_to_public" class="set_to_public_icon modal-trigger set_privacy_btn" data-document_id="1" data-document_privacy="private">Set to Public</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="1" data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="document_2" class="document_block">
                    <div class="document_details">
                        <input type="text" name="document_title" value="Company Handout" id="" class="document_title" readonly="">
                        <button class="invite_collaborators_btn modal-trigger" href="#modal1"> 1</button>
                    </div>
                    <div class="document_controls">
                        <button class="access_btn modal-trigger set_privacy_btn" href="#confirm_to_public" data-document_id="2" data-document_privacy="private"></button>
                        <button class="more_action_btn dropdown-trigger" data-target="document_more_actions_2">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="document_more_actions_2" class="dropdown-content more_action_list_private">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_archive" class="archive_icon modal-trigger archive_btn" data-document_id="2" data-documentation_action="archive">Archive</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#modal1" class="invite_icon modal-trigger">Invite</a></li>
                            <li class="divider" tabindex="-1"></li><li><a href="#confirm_to_public" class="set_to_public_icon modal-trigger set_privacy_btn" data-document_id="2" data-document_privacy="private">Set to Public</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="2" data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="document_3" class="document_block">
                    <div class="document_details">
                        <input type="text" name="document_title" value="Accounting" id="" class="document_title" readonly="">
                        
                    </div>
                    <div class="document_controls">
                        
                        <button class="more_action_btn dropdown-trigger" data-target="document_more_actions_3">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="document_more_actions_3" class="dropdown-content more_action_list_public">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_archive" class="archive_icon modal-trigger archive_btn" data-document_id="3" data-documentation_action="archive">Archive</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_private" class="set_to_private_icon modal-trigger set_privacy_btn" data-document_id="3" data-document_privacy="public">Set to Private</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="3" data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="document_4" class="document_block">
                    <div class="document_details">
                        <input type="text" name="document_title" value="Marketing" id="" class="document_title" readonly="">
                        
                    </div>
                    <div class="document_controls">
                        
                        <button class="more_action_btn dropdown-trigger" data-target="document_more_actions_4">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="document_more_actions_4" class="dropdown-content more_action_list_public">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_archive" class="archive_icon modal-trigger archive_btn" data-document_id="4" data-documentation_action="archive">Archive</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_private" class="set_to_private_icon modal-trigger set_privacy_btn" data-document_id="4" data-document_privacy="public">Set to Private</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="4" data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="document_5" class="document_block">
                    <div class="document_details">
                        <input type="text" name="document_title" value="Engineering" id="" class="document_title" readonly="">
                        <button class="invite_collaborators_btn modal-trigger" href="#modal1"> 6</button>
                    </div>
                    <div class="document_controls">
                        <button class="access_btn modal-trigger set_privacy_btn" href="#confirm_to_public" data-document_id="5" data-document_privacy="private"></button>
                        <button class="more_action_btn dropdown-trigger" data-target="document_more_actions_5">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="document_more_actions_5" class="dropdown-content more_action_list_private">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_archive" class="archive_icon modal-trigger archive_btn" data-document_id="5" data-documentation_action="archive">Archive</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#modal1" class="invite_icon modal-trigger">Invite</a></li>
                            <li class="divider" tabindex="-1"></li><li><a href="#confirm_to_public" class="set_to_public_icon modal-trigger set_privacy_btn" data-document_id="5" data-document_privacy="private">Set to Public</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="5" data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="document_6" class="document_block">
                    <div class="document_details">
                        <input type="text" name="document_title" value="Product Team" id="" class="document_title" readonly="">
                        <button class="invite_collaborators_btn modal-trigger" href="#modal1"> 9</button>
                    </div>
                    <div class="document_controls">
                        <button class="access_btn modal-trigger set_privacy_btn" href="#confirm_to_public" data-document_id="6" data-document_privacy="private"></button>
                        <button class="more_action_btn dropdown-trigger" data-target="document_more_actions_6">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="document_more_actions_6" class="dropdown-content more_action_list_private">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_archive" class="archive_icon modal-trigger archive_btn" data-document_id="6" data-documentation_action="archive">Archive</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#modal1" class="invite_icon modal-trigger">Invite</a></li>
                            <li class="divider" tabindex="-1"></li><li><a href="#confirm_to_public" class="set_to_public_icon modal-trigger set_privacy_btn" data-document_id="6" data-document_privacy="private">Set to Public</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="6" data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="document_7" class="document_block">
                    <div class="document_details">
                        <input type="text" name="document_title" value="UI/UX" id="" class="document_title" readonly="">
                        <button class="invite_collaborators_btn modal-trigger" href="#modal1"> 9</button>
                    </div>
                    <div class="document_controls">
                        <button class="access_btn modal-trigger set_privacy_btn" href="#confirm_to_public" data-document_id="7" data-document_privacy="private"></button>
                        <button class="more_action_btn dropdown-trigger" data-target="document_more_actions_7">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="document_more_actions_7" class="dropdown-content more_action_list_private">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_archive" class="archive_icon modal-trigger archive_btn" data-document_id="7" data-documentation_action="archive">Archive</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#modal1" class="invite_icon modal-trigger">Invite</a></li>
                            <li class="divider" tabindex="-1"></li><li><a href="#confirm_to_public" class="set_to_public_icon modal-trigger set_privacy_btn" data-document_id="7" data-document_privacy="private">Set to Public</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="7" data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="document_8" class="document_block">
                    <div class="document_details">
                        <input type="text" name="document_title" value="Admissions" id="" class="document_title" readonly="">
                        <button class="invite_collaborators_btn modal-trigger" href="#modal1"> 9</button>
                    </div>
                    <div class="document_controls">
                        <button class="access_btn modal-trigger set_privacy_btn" href="#confirm_to_public" data-document_id="8" data-document_privacy="private"></button>
                        <button class="more_action_btn dropdown-trigger" data-target="document_more_actions_8">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="document_more_actions_8" class="dropdown-content more_action_list_private">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_archive" class="archive_icon modal-trigger archive_btn" data-document_id="8" data-documentation_action="archive">Archive</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#modal1" class="invite_icon modal-trigger">Invite</a></li>
                            <li class="divider" tabindex="-1"></li><li><a href="#confirm_to_public" class="set_to_public_icon modal-trigger set_privacy_btn" data-document_id="8" data-document_privacy="private">Set to Public</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="8" data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="document_9" class="document_block">
                    <div class="document_details">
                        <input type="text" name="document_title" value="Trainee" id="" class="document_title" readonly="">
                        
                    </div>
                    <div class="document_controls">
                        
                        <button class="more_action_btn dropdown-trigger" data-target="document_more_actions_9">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="document_more_actions_9" class="dropdown-content more_action_list_public">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_archive" class="archive_icon modal-trigger archive_btn" data-document_id="9" data-documentation_action="archive">Archive</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_private" class="set_to_private_icon modal-trigger set_privacy_btn" data-document_id="9" data-document_privacy="public">Set to Private</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="9" data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="document_10" class="document_block">
                    <div class="document_details">
                        <input type="text" name="document_title" value="Instructors" id="" class="document_title" readonly="">
                        
                    </div>
                    <div class="document_controls">
                        
                        <button class="more_action_btn dropdown-trigger" data-target="document_more_actions_10">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="document_more_actions_10" class="dropdown-content more_action_list_public">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_archive" class="archive_icon modal-trigger archive_btn" data-document_id="10" data-documentation_action="archive">Archive</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_private" class="set_to_private_icon modal-trigger set_privacy_btn" data-document_id="10" data-document_privacy="public">Set to Private</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="10" data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="document_11" class="document_block">
                    <div class="document_details">
                        <input type="text" name="document_title" value="Business Leads" id="" class="document_title" readonly="">
                        <button class="invite_collaborators_btn modal-trigger" href="#modal1"> 10</button>
                    </div>
                    <div class="document_controls">
                        <button class="access_btn modal-trigger set_privacy_btn" href="#confirm_to_public" data-document_id="11" data-document_privacy="private"></button>
                        <button class="more_action_btn dropdown-trigger" data-target="document_more_actions_11">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="document_more_actions_11" class="dropdown-content more_action_list_private">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_archive" class="archive_icon modal-trigger archive_btn" data-document_id="11" data-documentation_action="archive">Archive</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#modal1" class="invite_icon modal-trigger">Invite</a></li>
                            <li class="divider" tabindex="-1"></li><li><a href="#confirm_to_public" class="set_to_public_icon modal-trigger set_privacy_btn" data-document_id="11" data-document_privacy="private">Set to Public</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="11" data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="document_12" class="document_block">
                    <div class="document_details">
                        <input type="text" name="document_title" value="Intern" id="" class="document_title" readonly="">
                        
                    </div>
                    <div class="document_controls">
                        
                        <button class="more_action_btn dropdown-trigger" data-target="document_more_actions_12">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="document_more_actions_12" class="dropdown-content more_action_list_public">
                            <li class="edit_title_btn"><a href="#!" class="edit_title_icon">Edit Title</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#!" class="duplicate_icon">Duplicate</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_archive" class="archive_icon modal-trigger archive_btn" data-document_id="12" data-documentation_action="archive">Archive</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_private" class="set_to_private_icon modal-trigger set_privacy_btn" data-document_id="12" data-document_privacy="public">Set to Private</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="12" data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div class="no_documents hidden">
                    <img src="https://village88.s3.us-east-1.amazonaws.com/boomyeah_v2/empty_illustration.png"
                        alt="Empty Content Illustration">
                    <p>You have no documentations yet</p>
                </div>
            </div>
            <div id="archived_documents" class="hidden">
                <div id="document_100" class="document_block">
                    <div class="document_details">
                        <input type="text" name="document_title" value="Company Handout" id="" class="document_title" readonly="">
                        <button class="invite_collaborators_btn modal-trigger archived_disabled" href="#modal1"> 1</button>
                    </div>
                    <div class="document_controls">
                        <button class="access_btn modal-trigger archived_disabled" href="#confirm_to_public"></button>
                        <button class="more_action_btn dropdown-trigger" data-target="document_more_actions_100">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="document_more_actions_100" class="dropdown-content more_action_list_private">
                            <li><a href="#confirm_to_archive" class="archive_icon modal-trigger archive_btn" data-document_id="100" data-documentation_action="archive">Archive</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="100" data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="document_101" class="document_block">
                    <div class="document_details">
                        <input type="text" name="document_title" value="Accounting" id="" class="document_title" readonly="">
                    </div>
                    <div class="document_controls">
                        <button class="more_action_btn dropdown-trigger" data-target="document_more_actions_101">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="document_more_actions_101" class="dropdown-content more_action_list_private">
                            <li><a href="#confirm_to_archive" class="archive_icon modal-trigger archive_btn" data-document_id="101" data-documentation_action="archive">Archive</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="101" data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div id="document_102" class="document_block">
                    <div class="document_details">
                        <input type="text" name="document_title" value="Engineering" id="" class="document_title" readonly="">
                        <button class="invite_collaborators_btn modal-trigger archived_disabled" href="#modal1"> 6</button>
                    </div>
                    <div class="document_controls">
                        <button class="access_btn modal-trigger archived_disabled" href="#confirm_to_public"></button>
                        <button class="more_action_btn dropdown-trigger" data-target="document_more_actions_102">⁝</button>
                        <!-- Dropdown Structure -->
                        <ul id="document_more_actions_102" class="dropdown-content more_action_list_private">
                            <li><a href="#confirm_to_archive" class="archive_icon modal-trigger archive_btn" data-document_id="102" data-documentation_action="archive">Archive</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="#confirm_to_remove" class="remove_icon modal-trigger remove_btn" data-document_id="102" data-documentation_action="remove">Remove</a></li>
                        </ul>
                    </div>
                </div>
                <div class="no_archived_documents hidden">
                    <img src="https://village88.s3.us-east-1.amazonaws.com/boomyeah_v2/empty_illustration.png"
                        alt="Empty Content Illustration">
                    <p>You have no archived documentations yet</p>
                </div>
            </div>
        </div>
    </div>
    <div id="confirmation_modal">
        <div id="confirm_to_public" class="modal">
            <div class="modal-content">
                <h4>Confirmation</h4>
                <p>Are you sure you want to change this documentation to Public?</p>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect btn-flat no_btn">No</a>
                <a href="#!" class="modal-close waves-effect btn-flat yes_btn change_privacy_yes_btn">Yes</a>
            </div>
        </div>
    </div>
    <div id="confirmation_modal_private">
        <div id="confirm_to_private" class="modal">
            <div class="modal-content">
                <h4>Confirmation</h4>
                <p>Are you sure you want to change this documentation to Private?</p>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect btn-flat no_btn">No</a>
                <a href="#!" class="modal-close waves-effect btn-flat yes_btn change_privacy_yes_btn">Yes</a>
            </div>
        </div>
    </div>
    <div id="confirmation_modal_archive">
        <div id="confirm_to_archive" class="modal">
            <div class="modal-content">
                <h4>Confirmation</h4>
                <p>Are you sure you want to move this documentation to Archive?</p>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect btn-flat no_btn">No</a>
                <a href="#!" id="archive_confirm" class="modal-close waves-effect btn-flat yes_btn">Yes</a>
            </div>
        </div>
    </div>
    <div id="confirmation_modal_remove">
        <div id="confirm_to_remove" class="modal">
            <div class="modal-content">
                <h4>Confirmation</h4>
                <p>Are you sure you want to remove this documentation?</p>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect btn-flat no_btn">No</a>
                <a href="#!" id="remove_confirm" class="modal-close waves-effect btn-flat yes_btn">Yes</a>
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
    <form id="change_document_privacy_form" action="#" method="POST" hidden>
        <input type="hidden" id="change_privacy_doc_id" name="document_id">
        <input type="hidden" id="change_privacy_doc_privacy" name="document_privacy">
    </form>
    <form id="remove_archive_form" action="#" method="POST" hidden>
        <input type="hidden" id="documentation_action" name="documentation_action">
        <input type="hidden" id="remove_archive_id" name="document_id">
    </form>
    <form action="remove_invited_user_form" action="#" method="POST" hidden>
        <input type="hidden" id="invited_user_id" name="invited_user_id">
    </form>
    <!--JavaScript at end of body for optimized loading-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="<?= add_file("assets/js/main_navigation.js") ?>"></script>
    <script src="<?= add_file("assets/js/admin_documentation.js") ?>"></script>
    <script src="<?= add_file("assets/js/invite_modal.js") ?>"></script>
    <script src="<?= add_file("assets/js/hotkeys.js") ?>"></script>
</body>

</html>