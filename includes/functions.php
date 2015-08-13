<?php
/**
 * This file is for defining static functions, these are functions
 * that will have a broader scope than just one class to reduce on
 * code dependency.
 */

$feed_number = 0;
/**
 * This extracts only the Text part of the description XML tag
 * Uses htmlentities on the string so symbols and HTML code
 * will be escaped and therefore will validate with W3C
 * @param $item : A single item tag from the feed
 * @return string
 */
function getDescriptionFromFeed($item)
{
    $description = ($item->description);
    $pos = strpos($item->description, "/>");

    $out = str_replace(" & ", " and ", substr($description,$pos+2,strlen($description)));
    $out = str_replace("<B>", "<b>",  $out);
    $out = str_replace("</B>", "</b>",$out);
    $out = str_replace("<I>", "<i>", $out);
    $out = str_replace("</I>", "</i>", $out);
    $out = str_replace("<br>", "<br />", $out);
    return $out;
}

function getCleanTitle($item)
{
    $out = str_replace(" & ", " and ",  $item);
    $out = str_replace("<B>", "<b>",  $out);
    $out = str_replace("</B>", "</b>",$out);
    $out = str_replace("<I>", "<i>", $out);
    $out = str_replace("</I>", "</i>", $out);
    $out = str_replace("<br>", "<br />", $out);
    return $out;
}

/**
 * This extracts only the image part of the description XML tag
 * @param $item : A single item tag from the feed
 * @return string
 */
function getImageFromFeed($item)
{
    $ext = "";
    $extensions = array(".jpg",".jpeg",".png",".gif");
    foreach($extensions as $extension)
    {
        if(strpos($item->description,$extension))
        {
            $ext = $extension;
        }
    }
    return substr($item->description,strpos($item->description,"http"),strpos($item->description,$ext)-6);
}

/**
 * This method is used to hide elements if the user
 * is logged in, for example the login form
 *
 * @param $logged_in bool
 * @return string : returns 'hide' if logged in
 */
function hideIfLoggedIn($logged_in)
{
    return $logged_in ? "hide" : "show";
}

/**
 * This method is used to show elements if the user
 * is logged in, for example the user bar
 *
 * @param $logged_in bool
 * @return string : returns 'show' if logged in
 */
function showIfLoggedIn($logged_in)
{
    return $logged_in ? "show" : "hide";
}

/**
 * This method is used to modify elements if
 * a user is logged in by outputting a class
 *
 * @param $logged_in bool
 * @return string : returns custom class if logged in
 */
function customIfLoggedIn($logged_in, $class_name)
{
    $out = "";
    if($logged_in)
    {
        $out = $class_name;
    }
    return $out;
}

/**
 * This method is for testing purposes most commonly
 * to print the $_SESSION and $_POST to the screen
 * however, this prepares the statement in HTML <pre>
 * tags so the output is human readable.
 *
 * @param $value Array : The value to prepare and print
 */
function print_pre($value) {
    echo "<pre>",print_r($value, true),"</pre>";
}

/**
 * This method redirects users to a url
 * can be used in conjunction with AdminPage.php
 * if the user is not an admin redirect to home.
 *
 * @param $url String : Url to redirect to
 */
function redirect_to($url)
{
    header("Location: {$url}");
    exit;
}


/**
 * This is the same method used in the database class
 * however, for a four line method code repetition is preferable
 * to code dependency
 *
 * @param $input String : the string passed by the user
 * @return string : Returns a cleaned string to prevent SQL injection.
 */
function clean_input($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlentities($input);
    return $input;
}