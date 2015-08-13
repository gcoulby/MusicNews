<?php

include_once 'includes/Database.php';

$error = ""; //This variable will hold any error codes to be passed with GET request
$db = new Database(); //Call to the database

/**
 * If the POST is not empty then save each
 * 'item' in the post to the variable $item
 * if the current item on any iteration has
 * the value 'checked' then if the item does not
 * exist in the database then save this article
 * to the database the $_POST variable for
 * subscriberID has been named ['TRANSID']
 * as it has been included in the forms hidden
 * field, this way web developers looking at
 * the code would not immediately draw the
 * connection between subscriberID and the ID
 * being passed to the $_POST.
 */
if(!empty($_POST))
{

    foreach($_POST['item'] as $item)
    {
        if(!empty($item['checked']))
        {
            if(count($db->find_all_from_table('saved_press_releases', "WHERE `title` = \"{$item['title']}\" AND `subscriberID` = \"{$_POST['TRANSID']}\""))==0)
            {
                if($db->save_article_to_database($_POST['TRANSID'], $item['link'] ,$item['title'],$item['description'],$item['image'], $item['date']))
                {
                    $error = 1;
                }
                else
                {
                    $error = 2;
                }
            }
            else
            {
                $error = 3;
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


