<?php
/**
 * This class allows the easy construction of 'Feeds' where by information can be extracted
 * from a Music News XML, RSS feed and displayed using the same formatting for each feed.
 */
class FeedItem {


    /**
     * Extracts the information from the Item tag and outputs
     * it surrounded by class named tags. HTML entities is used on
     * all information to avoid SQL injection, This FeedItem is for
     * one single press release and displays in a common box the image
     * title, description, publish date  and a checkbox with hidden fields
     * that integrate to a form in the FeedPage class although this creates
     * a dependency the pros outweigh the cons in terms of code
     * size and efficiency
     * @param $feed_item : A single item tag from the feed
     * @param $id : the id assigned to each feed item
     *
     */
    function __construct($feed_item, $id)
    {
        ?>
        <div class="feed_item">
            <div class="feed_item_body">
                <h2 class="feed_item_title">
                    <a href="<?php echo  htmlentities($feed_item->link); ?>" title="<?php echo htmlspecialchars_decode(getCleanTitle($feed_item->title)); ?>" onclick="target='_blank';">
                        <?php echo  htmlspecialchars_decode(getCleanTitle($feed_item->title)); ?> <!--Decode the Special Characters-->
                    </a>
                </h2>
                <span class="feed_item_date"><?php echo htmlentities($feed_item->pubDate); ?></span>

                <p class="feed_item_text"><!--<![CDATA[--><?php echo getDescriptionFromFeed($feed_item); ?><!--]]>--></p>

            </div><!--END 'feed_item_body'-->

            <div class="feed_item_image_block">
                <a href="<?php echo  htmlentities($feed_item->link); ?>" title="<?php echo  htmlspecialchars_decode(getCleanTitle($feed_item->title)); ?>" onclick="target='_blank';">
                    <img class="feed_item_image" src="<?php echo htmlentities(getImageFromFeed($feed_item)); ?>" alt="Music News Feed Image" />
                </a>
            </div>

            <div class="feed_item_select  <?php echo isset($_SESSION['logged_in']) ? showIfLoggedIn($_SESSION['logged_in']) : "hide"; ?>">
                <input type="hidden" name="item[<?php echo $id; ?>][title]" value="<?php echo htmlspecialchars_decode($feed_item->title); ?>" />
                <input type="hidden" name="item[<?php echo $id; ?>][description]" value="<?php echo htmlentities(getDescriptionFromFeed($feed_item)); ?>" />
                <input type="hidden" name="item[<?php echo $id; ?>][date]" value="<?php echo htmlentities($feed_item->pubDate); ?>" />
                <input type="hidden" name="item[<?php echo $id; ?>][image]" value="<?php echo htmlentities(getImageFromFeed($feed_item)); ?>" />
                <input type="hidden" name="item[<?php echo $id; ?>][link]" value="<?php echo htmlentities($feed_item->link); ?>" />
                <input class="feed_item_checkbox" type="checkbox" name="item[<?php echo $id; ?>][checked]" />
            </div>
        </div>
        <?php
    }

}