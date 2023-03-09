<?php
    session_start();

    // Sample admin session
    $_SESSION["user_id"]       = 1;
    $_SESSION["user_level_id"] = 9;
    $_SESSION["workspace_id"]  = 1;
    // END

    include_once("../view_helper.php");  
    include_once("../../config/connection.php");
    include_once("../../config/constants.php");
    $document_title = (isset($_GET["document_title"])) ? htmlspecialchars_decode( $_GET["document_title"] ) : "Employee Handbook";
    $section_title = (isset($_GET["section_title"])) ? htmlspecialchars_decode( $_GET["section_title"] ) : "About Company";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Company - Employee Handbook | BoomYEAH</title>
    <link rel="shortcut icon" href="../../../assets/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <link rel="stylesheet" href="<?= add_file("assets/css/vendor/redactorx.min.css") ?>">
    <link rel="stylesheet" href="<?= add_file("assets/css/admin_edit_section.css") ?>">
    <script src="<?= add_file("assets/js/vendor/Sortable.min.js") ?>"></script>
    <script src="<?= add_file("assets/js/vendor/ux.lib.js") ?>"></script>
    <script src="<?= add_file("assets/js/constants.js") ?>"></script>
</head>
<body>
    <div id="main_navigation"><?php include_once("../partials/main_navigation.php"); ?></div>
    <div id="wrapper" class="container">
        <div id="edit_section_content">
            <form action="#" method="POST" id="edit_section_form">
                <div id="section_summary">
                    <div class="breadcrumbs">
                        <ul id="breadcrumbs_list">
                            <li class="breadcrumb_item"><a href="admin_documentation.html">Documentations</a></li class="breadcrumb_item">
                            <li class="breadcrumb_item"><a href="admin_edit_documentation.html">Employee Handbook</a></li class="breadcrumb_item">
                            <li class="breadcrumb_item active"><span>About Company</span></li>
                        </ul>
                        <div class="row_placeholder"></div>
                        <a href="../default_data/preview_section.html" id="preview_section_btn">Preview</a>
                    </div>
                    <div class="section_details">
                        <h1 id="section_title">About Company</h1>

                        <div class="add_description">
                            <textarea name="section_short_description" id="section_short_description" placeholder="Add Description">Village 88 Inc. is a US-Delaware corporation which focuses on incubating companies and providing IT consultancy services to companies in the US. V88 also has a remote branch in San Fernando, La Union, Philippines registered in Securities and Exchange Commission as 457Avenue Inc.</textarea>
                        </div>
                    </div>
                </div>
                <div id="section_pages">
                    <div class="section_page_content">
                        <ul class="section_page_tabs">
                            <li class="page_tab_item active" data-tab_id="tab_1">
                                <a href="#tab_1">Our Mission &amp; Vision</a>
                                <button type="button" class="remove_tab_btn">&times;</button>
                            </li>
                            <li class="page_tab_item" data-tab_id="tab_2">
                                <a href="#tab_2">Corporate Responsibility</a>
                                <button type="button" class="remove_tab_btn">&times;</button>
                            </li>
                            <li class="page_tab_item" data-tab_id="tab_3">
                                <a href="#tab_3">Untitled Page</a>
                                <button type="button" class="remove_tab_btn">&times;</button>
                            </li>
                            <li class="add_page_tab">
                                <button class="add_page_btn" type="button">+</button>
                            </li>
                        </ul>
                        <div class="section_page_tab show" id="tab_1">
                            <input type="text" class="tab_title" value="Our Mission &amp; Vision">
                            <textarea id="tab_content_1" class="tab_content"><p>Village 88's vision is to incubate and launch businesses that bring positive impact to the world.</p><p>Since 2011, Village 88 has incubated Coding Dojo, Data Compass, Focus Tracker, and helped start numerous start-ups including Alumnify, SpotTrender, MatrixDS, and others.</p> <img src='https://cdn.village88.com/assets/images/homepage/the_villager_batangas.jpg'></textarea>
                            <div class="tab_footer">
                                <label for="allow_comments_tab_1" class="checkbox_label">
                                    <input type="checkbox" id="allow_comments_tab_1" name="allow_comments" checked>
                                    <div class="checkbox_marker"></div>     
                                    <span class="checkbox_text">Allow Comments</span>
                                </label>
                                <div class="row_placeholder"></div>
                                <div class="saving_indicator">Saving...</div>
                            </div>
                        </div>
                        <div class="section_page_tab" id="tab_2">
                            <input type="text"  class="tab_title" value="Corporate Responsibility">
                            <textarea class="tab_content"><p data-rx-type="paragraph" data-rx-first-level="true">Corporate responsibility in the tech industry refers to the ethical and moral obligations that companies have to stakeholders, including employees, customers, shareholders, the community, and the environment. In the tech industry, this encompasses a wide range of issues, such as data privacy, worker rights and fair labor practices, responsible sourcing of materials, energy efficiency and sustainability, and product safety and security.</p><p data-rx-type="paragraph" data-rx-first-level="true">For example, tech companies have a responsibility to protect the personal data of their customers and to be transparent about how that data is used. They also have a responsibility to ensure that their supply chain is free from forced labor and that workers are paid fair wages and treated with dignity. Companies have a responsibility to minimize their environmental impact and to work towards sustainable solutions for their operations and products.</p><p data-rx-type="paragraph" data-rx-first-level="true">Moreover, tech companies have a responsibility to be socially responsible and to contribute to the communities in which they operate. This can include supporting local charities and non-profit organizations, providing education and training programs, and working to bridge the digital divide by providing access to technology and the internet to underprivileged communities.</p><p data-rx-type="paragraph" data-rx-first-level="true" class="rx-block-focus">In short, corporate responsibility in the tech industry is about companies taking an ethical and sustainable approach to their operations, products, and relationships with stakeholders. It requires companies to consider the long-term impacts of their actions on society and the environment, and to take steps to minimize any negative consequences.</p></textarea>
                            <div class="tab_footer">
                                <label for="allow_comments_tab_2" class="checkbox_label">
                                    <input type="checkbox" id="allow_comments_tab_2" name="allow_comments" checked>
                                    <div class="checkbox_marker"></div>
                                    <span class="checkbox_text">Allow Comments</span>
                                </label>
                                <div class="row_placeholder"></div>
                                <div class="saving_indicator">Saving...</div>
                            </div>
                        </div>
                        <div class="section_page_tab" id="tab_3">
                            <input type="text" class="tab_title" value="Untitled Page">
                            <textarea class="tab_content">This is a description</textarea>
                            <div class="tab_footer">
                                <label for="allow_comments_tab_3" class="checkbox_label">
                                    <input type="checkbox" id="allow_comments_tab_3" name="allow_comments">
                                    <div class="checkbox_marker"></div>
                                    <span class="checkbox_text">Allow Comments</span>
                                </label>
                                <div class="row_placeholder"></div>
                                <div class="saving_indicator">Saving...</div>
                            </div>
                        </div>
                    </div>
                    <div class="section_page_content">
                        <ul class="section_page_tabs">
                            <li class="page_tab_item active" data-tab_id="tab_4">
                                <a href="#tab_4">Village88 2012</a>
                                <button type="button" class="remove_tab_btn">&times;</button>
                            </li>
                            <li class="page_tab_item" data-tab_id="tab_5">
                                <a href="#tab_5">Village88 2013</a>
                                <button type="button" class="remove_tab_btn">&times;</button>
                            </li>
                            <li class="page_tab_item" data-tab_id="tab_6">
                                <a href="#tab_6">Village88 2014</a>
                                <button type="button" class="remove_tab_btn">&times;</button>
                            </li>
                            <li class="add_page_tab">
                                <button class="add_page_btn" type="button">+</button>
                            </li>
                        </ul>
                        <div class="section_page_tab show" id="tab_4">
                            <input type="text" class="tab_title" value="Village88 2012">
                            <textarea id="tab_content_1" class="tab_content">Village 88's vision is to incubate and launch businesses that bring positive impact to the world.
                                Since 2011, Village 88 has incubated Coding Dojo, Data Compass, Focus Tracker, and helped start numerous start-ups including Alumnify, SpotTrender, MatrixDS, and others.
                                Coding Dojo was founded in 2012. Its first campus opened in Mountain View, CA, U.S.A. Then later campuses were opened in Bellevue Washington, Burbank California, Oakland California, Chicago Illinois, Washington DC, Tulsa Arizona, Dallas Texas, Boise Idaho, and Orange County California
                                Coding Dojo offers various coding curriculum to help people learn how to code in a fully immersive setting. Coding Dojo's learn platform, CRM, its website, and various tools were all built by the Village 88 team.
                                Data Compass is an assessment platform where hiring managers can know the Programming IQ of their developer candidates. Data Compass outperforms the market by achieving concurrent criterion validation for its Programming IQ assessment.
                                In psychometrics, concurrent criterion validation refers to a comparison between the measure in question and an outcome assessed at the same time. Data Compass completed its concurrent criterion validation study in March 2019.</textarea>
                            <div class="tab_footer">
                                <label for="allow_comments_tab_4" class="checkbox_label">
                                    <input type="checkbox" id="allow_comments_tab_4" name="allow_comments">
                                    <div class="checkbox_marker"></div>
                                    <span class="checkbox_text">Allow Comments</span>
                                </label>
                                <div class="row_placeholder"></div>
                                <div class="saving_indicator">Saving...</div>
                            </div>
                        </div>
                        <div class="section_page_tab" id="tab_5">
                            <input type="text"  class="tab_title" value="Village88 2013">
                            <textarea class="tab_content">Village 88's vision is to incubate and launch businesses that bring positive impact to the world.
                                Since 2011, Village 88 has incubated Coding Dojo, Data Compass, Focus Tracker, and helped start numerous start-ups including Alumnify, SpotTrender, MatrixDS, and others.
                                </textarea>
                            <div class="tab_footer">
                                <label for="allow_comments_tab_5" class="checkbox_label">
                                    <input type="checkbox" id="allow_comments_tab_5" name="allow_comments">
                                    <div class="checkbox_marker"></div>
                                    <span class="checkbox_text">Allow Comments</span>
                                </label>
                                <div class="row_placeholder"></div>
                                <div class="saving_indicator">Saving...</div>
                            </div>
                        </div>
                        <div class="section_page_tab" id="tab_6">
                            <input type="text" class="tab_title" value="Village88 2014">
                            <textarea class="tab_content">Village 88's vision is to incubate and launch businesses that bring positive impact to the world.
                                Since 2011, Village 88 has incubated Coding Dojo, Data Compass, Focus Tracker, and helped start numerous start-ups including Alumnify, SpotTrender, MatrixDS, and others.
                                Coding Dojo was founded in 2012. Its first campus opened in Mountain View, CA, U.S.A. Then later campuses were opened in Bellevue Washington, Burbank California, Oakland California, Chicago Illinois, Washington DC, Tulsa Arizona, Dallas Texas, Boise Idaho, and Orange County California
                                Coding Dojo offers various coding curriculum to help people learn how to code in a fully immersive setting. Coding Dojo's learn platform, CRM, its website, and various tools were all built by the Village 88 team.
                                Data Compass is an assessment platform where hiring managers can know the Programming IQ of their developer candidates. </textarea>
                            <div class="tab_footer">
                                <label for="allow_comments_tab_6" class="checkbox_label">
                                    <input type="checkbox" id="allow_comments_tab_6" name="allow_comments">
                                    <div class="checkbox_marker"></div>
                                    <span class="checkbox_text">Allow Comments</span>
                                </label>
                                <div class="row_placeholder"></div>
                                <div class="saving_indicator">Saving...</div>
                            </div>
                        </div>
                    </div>
                    <div class="section_page_content">
                        <ul class="section_page_tabs">
                            <li class="page_tab_item active" data-tab_id="tab_7">
                                <a href="#tab_7">The Village88 Training</a>
                                <button type="button" class="remove_tab_btn">&times;</button>
                            </li>
                            <li class="add_page_tab">
                                <button class="add_page_btn" type="button">+</button>
                            </li>
                        </ul>
                        <div class="section_page_tab show" id="tab_7">
                            <input type="text" class="tab_title" value="About the Village88 Training">
                            <textarea id="tab_content_1" class="tab_content"><p data-rx-type="paragraph" data-rx-first-level="true" class="rx-block-focus"><b>Village 88 Training is designed to help you level up your coding skills in just 16 weeks and get hired faster by companies of your choice.</b></p><p data-rx-type="paragraph" class="" data-rx-first-level="true"><b>The training program we are offering to the world through Coding Dojo is now offered exclusively to Filipinos for a reasonable price: YOUR COMMITMENT.</b><br></p><p data-rx-type="paragraph" class="" data-rx-first-level="true">Since offering the training in 2020, our alums now work in over 25 companies in the Philippines and internationally and get hired within two months after graduating.</p><figure data-embed-code="%3Ciframe%20width=%221221%22%20height=%22687%22%20src=%22https://www.youtube.com/embed/er5eVqMq9gU%22%20title=%22How%20Village%2088%20Free%20Online%20Training%20Started%20Here%20in%20the%20Philippines%20and%20Why%20We%20Offer%20It%20For%20FREE%22%20frameborder=%220%22%20allow=%22accelerometer;%20autoplay;%20clipboard-write;%20encrypted-media;%20gyroscope;%20picture-in-picture;%20web-share%22%20allowfullscreen=%22%22%3E%3C/iframe%3E" data-rx-type="embed" contenteditable="false" data-rx-first-level="true" class="" tabindex="-1"><div class="embed-responsive"><iframe width="1221" height="687" src="https://www.youtube.com/embed/er5eVqMq9gU" title="How Village 88 Free Online Training Started Here in the Philippines and Why We Offer It For FREE" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen=""></iframe></div><figcaption data-placeholder="Type caption (optional)" data-rx-type="figcaption" contenteditable="true">How Village 88 Free Online Training Started Here in the Philippines and Why We Offer It For FREE</figcaption></figure></textarea>
                            <div class="tab_footer">
                                <label for="allow_comments_tab_7" class="checkbox_label">
                                    <input type="checkbox" id="allow_comments_tab_7" name="allow_comments" checked>
                                    <div class="checkbox_marker"></div>
                                    <span class="checkbox_text">Allow Comments</span>
                                </label>
                                <div class="row_placeholder"></div>
                                <div class="saving_indicator">Saving...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <button id="add_page_tabs_btn" type="button">+ Add New</button>
            <div id="clone_section_page">
                <div class="section_page_content">
                    <ul class="section_page_tabs">
                        <li class="page_tab_item">
                            <a href="#tab_">Untitled Tab</a>
                            <button type="button" class="remove_tab_btn">&times;</button>
                        </li>
                        <li class="add_page_tab">
                            <button class="add_page_btn">+</button>
                        </li>
                    </ul>
                    <div class="section_page_tab">
                        <input type="text" class="tab_title" value="Untitled Tab">
                        <textarea class="tab_content">This is a description</textarea>
                        <div class="tab_footer">
                            <label for="allow_comments_" class="checkbox_label">
                                <input type="checkbox" id="allow_comments_" name="allow_comments">
                                <div class="checkbox_marker"></div>
                                <span class="checkbox_text">Allow Comments</span>
                            </label>
                            <div class="row_placeholder"></div>
                            <div class="saving_indicator">Saving...</div>
                        </div>
                    </div>
                </div>
                <?php include_once("../partials/clone_section_page.php"); ?>
            </div>
            <div id="modals_container">
                <?php include_once("../partials/confirm_action_modals.php"); ?>
            </div>
        </div>
    </div>
    <script src="<?= add_file("assets/js/vendor/redactorx.min.js") ?>"></script>
    <script src="<?= add_file("assets/js/custom/admin_edit_section/admin_edit_section_fe.js") ?>"></script>
    <script src="<?= add_file("assets/js/custom/admin_edit_section/admin_edit_section_be.js") ?>"></script>
</body>
</html>