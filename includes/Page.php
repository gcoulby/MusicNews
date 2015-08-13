<?php
/**
 * This will be the class used to create pages
 * The class has been made abstract so that instantiations
 * must be created following the same format
 * @author: Coulby
 * @version: 23/02/2015
 */

include_once 'includes/includes.php';

abstract class Page
{
    protected $stylesheet; //The single stylesheet that will be used by a page
    protected $styles; //Array of styles that will be added for extra style particular to a certain page
    protected $error; //Array of Errors that may be thrown during database calls
    protected $db; //The Database object which houses database methods and PDO object
    protected $feeds; //Array of press releases pulled from a url

    /**
     * The constructor for Page class, this is where the magic
     * happens for each page. The constructor starts/reads session
     * connects to the database and builds the page. This class is
     * abstract and can not be instantiated; therefore, sub classes
     * are needed which create the bodyContent(); method.
     * This method takes a lazy variable for authors this can either
     * be a string or an array. The common use case is that there will
     * be one author per page. However, occasionally there may be more
     * so in that instance an array will be accepted, given that all
     * strings will be converted to an array before being used.
     *
     *
     * @param $authors Page : a lazy variable which can either
     * be a string or an array. Accepted strings are 'Barber',
     * 'Carr' and 'Coulby'
     * @param $arrayOfStyles Page : Array of stylesheets to
     * be loaded into the header of each page. Stylesheets
     * must be stored in css directory or a sub directory therein
     * @author Coulby
     * @version 23/02/2015
     */
    public function __construct($arrayOfStyles, $show_widget, $isAdmin)
    {
        $this->feeds = simplexml_load_file("http://www.music-news.com/rss/news.asp");
        session_start(); //Start a new session or repoen existing sessions
        $this->db = new Database(); // Create a new Database object
        /**
         * Get request to logout, destroys the session
         * and makes a new one. Also sets the
         * $_SESSION['logged_in'] to false so that
         * undefined variable call error is not triggered
         */
        if(isset($_GET['lo']))
        {
            session_destroy();
            session_start();
            $_SESSION['logged_in'] = false;
        }

        /**
         * If the $_POST['login] is set then
         * validate the username and password
         * using the validate_login_credentials
         * method from inside the Database Object
         * if this returns as true check the table
         * for a match. The row that is returned
         * will have the password stripped from it
         * and the entire array will be saved to
         * the session
         * $_SESSION['logged_in'] == true
         * update the subscriber table to include
         * the date and time of the last login
         */
        if(isset($_POST['login']))
        {
            if($_POST['username_login'] && $_POST['password_login'])
            {
                if($this->db->validate_login_credentials($_POST['username_login'], $_POST['password_login'], 'subscriber'))
                {
                    $row = $this->db->check_table_for_match('subscriber', 'email', $_POST['username_login']);
                    unset($row['password']);
                    $_SESSION = $row;
                    $_SESSION['logged_in'] = true;
                    $this->db->setLastLogin($row['subscriberID']);
                }
            }
        }
        $this->styles = $arrayOfStyles;

        $this->insertHeader(); //Insert the common header (negates the need for header.php
        $this->openBody($show_widget); // This is the start of the body that is common to all pages.
    }

    /**
     * This forms the header and negates the need for a
     * header.php file.
     * All Pages will have this at the top of the page
     * ensuring the whole site has the same look and feel
     */
    protected function insertHeader()
    {
        ?>
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
            "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">

        <head>
            <title>Music News | CM0667 | Graham Coulby | </title>
            <meta http-equiv="content-type" content="text/html; charset=utf-8" />
            <meta name="viewport" content="width=device-width,initial-scale=1.0" />
            <meta name="google-translate-customization" content="793c297875327110-cdf7171c51df1687-g46478e8912cc4f8b-31" />
            <link rel="stylesheet" type="text/css" href="css/<?php echo $this->stylesheet; ?>"/>
            <?php
            /**
             * if an array of styles has been added on instantiation
             * add an XHTML format stylesheet reference for each
             */
                if(!is_null($this->styles))
                {
                    foreach($this->styles as $style)
                    {
                        ?>
                        <link rel="stylesheet" type="text/css" href="/css/<?php echo $style; ?>"/>
                    <?php
                    }
                }
            ?>
            <link href='http://fonts.googleapis.com/css?family=Lato:400,700,400italic' rel='stylesheet' type='text/css' /><!--(Google Fonts, 2015)-->
            <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript"></script>
        </head>
        <?php
    }

    /**
     * This is the start of the body that is common to all pages.
     * @param $show_widget BOOLEAN states whether the page should
     * have the widget on or not
     * >>Will be implemented in SubClass PublicPage
     */
    protected abstract function openBody($show_widget);

    /**
     * This forms the common footer and negates
     * the need for a footer.php file.
     * All Pages will have this at the bottom of the page
     * ensuring the whole site has the same look and feel
     * >>Will be implemented in SubClass PublicPage
     */
    protected abstract function insertFooter();
}