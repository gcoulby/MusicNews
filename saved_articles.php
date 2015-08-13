<?php
/**
 *
 * @author: Coulby
 * @version: 04/03/2015
 */

include_once 'includes/includes.php';

class SavedArticles extends PublicPage
{
    private $articles = array();

    protected function bodyContent()
    {
        echo "<h1>Your Saved Articles</h1>";

        if(isset($_GET['er']))
        {
            switch($_GET['er'])
            {
                case 0:
                    break;
                case 1:
                    echo "<p>Article(s) saved successfully.</p>";
                    break;
                case 2:
                    echo "<p>Error saving article.</p>";
                    break;
                case 3:
                    echo "<p>You have already saved this article.</p>";
                    break;
                case 4:
                    echo "<p>Article(s) deleted successfully.</p>";
                    break;
                case 5:
                    echo "<p>Error deleting article.</p>";
                    break;
            }
        }
        if(isset($_SESSION['subscriberID']))
        {
            $this->articles = $this->db->find_all_from_table("saved_press_releases", "WHERE subscriberID = \"{$_SESSION['subscriberID']}\"");
        }
        ?>

        <form action="delete_articles.php" method="post">
            <fieldset>
                <?php
                if(sizeof($this->articles) > 0)
                {
                    foreach ($this->articles as $article)
                    {
                        new SavedArticle($article);
                    }
                }
                else
                {
                    echo "<p>You currently do not have any saved articles.</p>";
                }
                ?>
                <input id="save_art_sub" type="submit" name="delete_articles" value="Delete Selected Articles" />
            </fieldset>
        </form><!--END form 'save_articles'-->



        <script type="text/javascript">
            <!--
            var checkboxes = document.getElementsByClassName('feed_item_checkbox');

            $('.feed_item_checkbox').change(function()
            {
                var count = 0;
                for (var i=0; i<checkboxes.length; i++)
                {
                    if(checkboxes[i].checked == true)
                    {
                        count++;
                    }
                }
                if(count>0)
                {
                    $('#save_art_sub').css('display', 'block');
                }
                else
                {
                    $('#save_art_sub').css('display', 'none');
                }
            });
            -->
        </script>

    <?php

    }
}
new SavedArticles(null,false);