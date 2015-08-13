<?php
/**
 * This class allows the easy construction of saved articles where by information can be extracted
 * from the database and displayed using the same formatting for each article.
 */
class SavedArticle {

    /**
     * Extracts the information from the Item tag and outputs
     * it surrounded by class named tags. HTML entities is used on
     * all information to avoid SQL injection, This SavedArticle is for
     * one single press release that is saved in the database
     * and displays in a common box the imagetitle, description,
     * publish date  and a checkbox with hidden fields
     * that integrate to a form in the FeedPage class although this creates
     * a dependency the pros outweigh the cons in terms of code
     * size and efficiency
     * @param $article : A single article from the database
     */
    function __construct($article)
    {
        ?>
        <div class="feed_item">
            <div class="feed_item_body">
                <h2 class="feed_item_title">
                    <a href="<?php echo  $article['link']; ?>" title="<?php echo $article['title']; ?>" onclick="target='_blank';">
                        <?php echo $article['title']; ?>
                    </a>
                </h2>
                <span class="feed_item_date"><?php echo $article['datePublished']; ?></span>

                <p class="feed_item_text"><?php echo $article['description']; ?></p>

            </div><!--END 'feed_item_body'-->

            <div class="feed_item_image_block">
                <a href="<?php echo  $article['link']; ?>" title="<?php echo $article['title']; ?>" onclick="target='_blank';">
                    <img class="feed_item_image" src="<?php echo $article['image']; ?>" alt="Music News Feed Image" />
                </a>
            </div>

            <div class="feed_item_select  <?php echo isset($_SESSION['logged_in']) ? showIfLoggedIn($_SESSION['logged_in']) : "hide"; ?>">
                <input type="hidden" name="item[<?php echo $article['pressReleaseID']; ?>][pressReleaseID]" value="<?php echo  $article['pressReleaseID']; ?>" />
                <input type="hidden" name="item[<?php echo $article['pressReleaseID']; ?>][subscriberID]" value="<?php echo  $article['subscriberID']; ?>" />
                <input class="feed_item_checkbox" type="checkbox" name="item[<?php echo $article['pressReleaseID']; ?>][checked]" />
            </div>
        </div>
    <?php
    }

}