<?php

include_once 'includes/Database.php';

$error = ""; //This variable will hold any error codes to be passed with GET request
$db = new Database(); //Call to the database

/**
 * If the POST is not empty then save each
 * 'item' in the post to the variable $item
 * if the current item on any iteration has
 * the value 'checked' then if the item exists
 * in the database (which it should, but protects
 * against errors just in case) then delete the row
 * by passing in 2 conditions.
 */
if(!empty($_POST))
{
    foreach($_POST['item'] as $item)
    {
        if(!empty($item['checked']))
        {
            if(count($db->find_all_from_table('saved_press_releases', "WHERE `pressReleaseID` = \"{$item['pressReleaseID']}\" AND `subscriberID` = \"{$item['subscriberID']}\""))>0)
            {
                if($db->delete_row_by_two_conditions('saved_press_releases', "`pressReleaseID`", $item['pressReleaseID'], "`subscriberID`",$item['subscriberID']))
                {
                    $error = 4;
                }
                else
                {
                    $error = 5;
                }
            }
        }
    }
}
/**
 * This sets the header to a new location and exits
 * this function will only work if no HTML has been
 * output, depending on the settings, will work on
 * some servers if output buffering is enabled. For
 * this to work 'exit;' must be called.
 * This also passes the error code as a get request
 * so that the page it forwards to can output the error
 */
header("Location: saved_articles.php?er={$error}");
exit;


