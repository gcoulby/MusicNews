<?php
/**
 * This will be the class used to create pages
 * The class has been made abstract so that instantiations
 * must be created following the same format
 * @author: Coulby
 * @version: 23/02/2015
 */

include_once 'includes/includes.php';

abstract class PublicPage extends Page
{

    /**
     * The constructor for PublicPage
     * @param Page $arrayOfStyles ARRAY array of styles to customise specific pages
     * @param $show_widget BOOLEAN determines whether widget is shown on a page
     */
    function __construct($arrayOfStyles, $show_widget)
    {
        $this->stylesheet = 'style.css'; //sets the stylesheet for all PublicPages
        parent::__construct($arrayOfStyles, $show_widget, false); //calls the constructor and passes parameters up the chain
        $this->bodyContent(); //calls the bodyContent... abstract method that will hold page content
        $this->insertFooter(); //calls the footer method.
    }

    /**
     * This method draws the parts of the body that
     * are the same on all public pages, such as
     * navigation and search boxes etc
     * This method mainly uses php to add classes
     * depending on the users state within the system
     * @param bool $show_widget
     */
    protected function openBody($show_widget)
    {
        ?>
        <body class="<?php echo (isset($_SESSION['logged_in'])) ? (customIfLoggedIn($_SESSION['logged_in'], "admin_bar_margin")) : ""; ?>"><!--Add class if logged in-->
        <div id="header">
            <div id="user_bar" class="<?php echo (isset($_SESSION['logged_in'])) ? (showIfLoggedIn($_SESSION['logged_in'])) : "hide"; ?>"><!--Ternary to switch class between show and hide-->
                <p class="saved_art button"><a class="<?php echo ($_SERVER['SCRIPT_NAME'] == "saved_articles.php") ? "selected " : ""; ?>" href="/saved_articles.php">View Your Saved Articles</a></p>
                <p class="logout button"><a href="?lo=1">Logout</a></p>
            </div>

            <div class="logo_image">
                <a href="index.php"><img id="logo" src="img/Music-News-EQ.png" alt="Music News Logo" /></a>
            </div> <!--END 'logo_image'-->

            <div id="login" class="<?php echo (isset($_SESSION['logged_in'])) ? hideIfLoggedIn($_SESSION['logged_in']) : ""; ?>">
                <form action="" method="post">
                    <fieldset>
                        <label class="label">Username:</label><input class="inputField" name="username_login" type="text" size="30" /><br />
                        <label class="label">Password:</label><input class="inputField" name="password_login" type="password" size="30" /><br />
                        <input class="submit button" type="submit" name="login" value="Login"/>
                    </fieldset>
                </form>
            </div><!--END 'login'-->

            <div id="welcome_note" class="<?php echo (isset($_SESSION['logged_in'])) ? showIfLoggedIn($_SESSION['logged_in']) : "hide"; ?>">
                <p>Welcome <?php echo isset($_SESSION['name']) ? substr($_SESSION['name'],0,strpos($_SESSION['name']," ")) : ""; ?></p>
                <p>You last logged in at <b><?php echo isset($_SESSION['name']) ? date("H:i:s",strtotime($_SESSION['lastlogin'])) : ""; ?></b> on the <b><?php echo isset($_SESSION['name']) ? date("jS F Y",strtotime($_SESSION['lastlogin'])) : ""; ?></b></p>
            </div>

            <div id="nav">
                <ul>
                    <li><a class="<?php echo ($_SERVER['SCRIPT_NAME'] == "index.php") ? "selected " : ""; ?>" href="/index.php">News</a></li>
                    <li><a class="<?php echo ($_SERVER['SCRIPT_NAME'] == "reviews.php") ? "selected " : ""; ?>" href="/reviews.php">Reviews</a></li>
                    <li><a class="<?php echo ($_SERVER['SCRIPT_NAME'] == "interviews.php") ? "selected " : ""; ?>" href="/interviews.php">Interviews</a></li>
                    <li><a class="<?php echo ($_SERVER['SCRIPT_NAME'] == "competitions.php") ? "selected " : ""; ?>" href="/competitions.php">Competitions</a></li>
                </ul>
                <div id="google_translate_element"></div>
                <script type="text/javascript">
                    function googleTranslateElementInit() {
                        new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
                    }
                </script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                    <form class="searchForm" action="search.php" method="post">
                    <fieldset>
                        <label class="searchLabel">Search News: </label>
                        <input class="searchField" name="search_criteria" type="text" size="35" />
                        <input class="submit searchSubmit" type="submit" name="search" value="\"/>
                    </fieldset>
                </form>
            </div><!--END 'nav'-->
        </div> <!--END 'header'-->
        <?php
            if($show_widget) //If the show_widget boolean is true then show the widget
            {
                ?>
                    <div id="sidebar">
                        <?php new Widget("Suggested Articles", $this->feeds); ?>
                    </div><!--END 'sidebar'-->
                <?php
            }
        ?>

        <div id="container" class="<?php echo $show_widget ? "with_widget" : ""; ?>"><!--This is where all the pages will be loaded into-->
        <?php
    }


    /**
     * This forms the common footer and negates
     * the need for a footer.php file.
     * All Pages will have this at the bottom of the page
     * ensuring the whole site has the same look and feel
     */
    protected function insertFooter()
    {
        ?>
                </div><!--END 'container' (header.php)-->
                <div id="footer">
                    <p>Copyright &copy; <?PHP echo date("Y"); ?> - Graham Coulby w14002403</p>
                    <p>Module: CM0667 - Object Oriented &amp; Web Programming</p>
                    <p>Submission Date: 26th May 2015</p>
                    <p style="float:right; margin-top:-3em;">
                        <a href="http://validator.w3.org/check?uri=referer"><img
                            src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Strict" height="31" width="88" /></a>
                    </p>
                </div>

                <script type="text/javascript">
                <!--
                    /**
                     * JQuery code to add class to widget on scroll down
                     *
                     * Loaded in footer.php so that all content is
                     * loaded prior to jQuery execution
                     */

                    jQuery(window).scroll(function()
                    {
                        var scrollPosition = jQuery(window).scrollTop(); // Position on screen via scrolling
        //                var userBarMargin = $('#user_bar').hasClass("show") ? "user_bar_margin" : "";

                        var headerHeight = 370; //Height of Header

                        /**
                         * If the position on the screen (in px) is greater than
                         * the height of the header (in px) add class 'scroll' to #sidebar
                         * if not remove the class
                         */
                        if(scrollPosition > headerHeight)
                        {
                            jQuery('#sidebar').addClass('scroll').addClass('user_bar_margin');
        //                    jQuery('#sidebar');
                        }else
                        {
                            jQuery('#sidebar').removeClass('scroll').removeClass('user_bar_margin');
                        }
                    });

                    var checkboxes = document.getElementsByClassName('feed_item_checkbox');
                    var chblength = checkboxes.length;

                    for (var i=0; i<chblength; i++)
                    {
                        checkboxes[i].checked = false;
                    }
                    -->
                </script>
            </body>
        </html>
        <?php
    }
    /**
     * This is where HTML and php are put
     * The contents of this method are displayed
     * between the header and the footer
     * >> implemented per page
     */
    protected abstract function bodyContent();
}