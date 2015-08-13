<?php
/**
 * This class allows the easy construction of 'Feed Pages' where by information can be extracted
 * from a Music News XML, RSS feed and displayed using the same formatting.
 */
class FeedPage {

    /**
     * Constructor for the FeedPage Class
     * Builds the feed pages but also controls the XPath searching
     * @param $url : The url where the RSS feed exists.
     */
    function __construct($url)
    {
        ?>

        <form action="save_articles.php" method="post">
            <fieldset>
                <input type="hidden" name="URL" value="<?php echo $url; ?>" />
                <input type="hidden" name="TRANSID" value="<?php echo isset($_SESSION['subscriberID']) ? $_SESSION['subscriberID'] : ""; ?>" /> <!--The subscriberID is posted as TRANSID so not obvious what it is-->
                    <?php
                    if(isset($_GET['page']))//checks to see if the get request has sent a page number or sets page to 1 if not
                    {
                        $currentPage = $_GET['page'];
                    }
                    else
                    {
                        $currentPage = 1;
                    }


                    if(isset($_POST['search']))//If the user has posted a search
                    {

                        $criteria = strtolower($_POST['search_criteria']); //The text entered into the search box (in lower case)
                        $feeds = simplexml_load_file($url); //The XML feed as an object
                        /**
                         * XPATH Search
                         *
                         * Searches all title elements "//title"
                         * and all description elements "//description"
                         * checks to see if the text contains a match of the string
                         *
                         * This first uses the translate function to turn
                         * the XML title/description to lowercase strings
                         *
                         * compares the lower case strings to the lowercase criteria.
                         *
                         */
                        $results = $feeds->xpath("//title[text()[contains(translate(.,
                                  'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
                                  'abcdefghijklmnopqrstuvwxyz'),'{$criteria}')]] | //description[text()[contains(translate(.,
                                  'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
                                  'abcdefghijklmnopqrstuvwxyz'),'{$criteria}')]]");

                        $i = 0;
                        $item_titles = array();
                        $items = array();
                        foreach($feeds->channel->item as $item) //save feed items as $item
                        {
                            foreach ($results as $result) //save the search results as $result
                            {
                                if($item->title == strval($result) || $item->description == strval($result)) //if this current item is equal to this current result
                                {
                                    if(!in_array($item->title,$item_titles))//If this title does not already exist in the array add it. Stops article repetition
                                    {
                                        $items[] = $item;
                                        $item_titles[] = $item->title;
                                    }
                                }
                            }
                        }
                        echo isset($results) ? "Your search returned " . count($items) . " results.<br /><br />" : ""; //Echo feedback to user
                        foreach($items as $item)//for each item instantiate a formatted FeedItem
                        {
                            new FeedItem($item, $i);
                            $i++;
                        }
                    }
                    else { //if not a search show ten items per page
                        $feeds = simplexml_load_file($url);
                        $i = 0;
                        $j=($currentPage-1)*10; //if page 2 show items 11-20
                        for ($j; $j < ($currentPage * 10); $j++) {
                            new FeedItem($feeds->channel->item[$j], $i);
                            $i++;
                        }
                    }

                    ?>
                <input id="save_art_sub" type="submit" name="save_articles" value="Save Selected Articles" />
            </fieldset>
        </form><!--END form 'save_articles'-->
            <?php
                $this->pagination($currentPage,$feeds);
            ?>

        <script type="text/javascript">
        <!--

        /**
         * This Jqeury is what makes the Save articles
         * button appear at the top of the page if an
         * article is selected. This means, due to the
         * user menu being in a fixed position that the
         * save articles button is visible from anywhere
         * on the page.
         * @type {NodeList}
         */

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

    public function pagination($currentPage, $feeds)
    {
        if(!isset($_POST['search']))//Don't show pagination on search.
        {
            ?>
            <div class="pagination" style="width:70%">
                <?php
                /**
                 * These element are place backwards, and for loop iterates backwards.
                 * This is because the pagination
                 * will be floated right of its container.
                 */
                if($currentPage < ($feeds->channel->item->count() / 10))//Do not show on last page
                {
                    ?>
                    <div class="pagination-block">
                        <a href='?page=<?php echo ($feeds->channel->item->count() / 10); ?>'>&gt;&gt;</a>
                    </div>
                <?php
                }

                if($currentPage < (($feeds->channel->item->count() / 10) -1))// do not show on penultimate page
                {
                    ?>
                    <div class="pagination-block">
                        <a href='?page=<?php echo $currentPage+1; ?>'>&gt;</a>
                    </div>
                <?php
                }

                for($i=($feeds->channel->item->count() / 10);$i>=1;$i--)
                {
                    ?>
                    <div class="pagination-block<?php echo ($currentPage == $i) ? " current_page" : ""; ?>">
                        <a href='?page=<?php echo $i; ?>'><?php echo $i; ?></a>
                    </div>
                <?php
                }
                if($currentPage > 2)//Do not show on page 2
                {
                    ?>
                    <div class="pagination-block">
                        <a href='?page=<?php echo $currentPage-1; ?>'>&lt;</a>
                    </div>
                <?php
                }
                if($currentPage > 1)//Do not show on page 1
                {
                    ?>
                    <div class="pagination-block">
                        <a href='?page=1'>&lt;&lt;</a>
                    </div>
                <?php
                }
                ?>
            </div>
        <?php
        }
    }
}