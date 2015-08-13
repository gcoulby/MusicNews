<?php
/**
 * This is a class to handle the sidebar panel widget
 */

class Widget {

    /**
     * Searches through an RSS Feed and outputs
     * the image and title to the widget that appears in
     * the right hand side of the screen this is made
     * to look like suggested items that would appear
     * on sites like amazon or ebay.
     *
     * Note: Image navigation can be considered clutter
     * which is problematic for deaf people. However, given
     * that this is a Music News site I think this is an
     * accessibility concern that can be overlooked.
     *
     * @param $title String : Title of the widget
     * @param $feeds array : The RSS feed to search through
     */
    function __construct($title, $feeds)
    {
        ?>
        <div class="widget">
            <h2 class="widget_title"><?php echo $title; ?></h2>
            <div class="widget_body">
                <?php
                    $arrayPositions = $this->getArrayPositions();
                    foreach($arrayPositions as $pos)
                    {
                        echo "<a href=\"";
                        echo htmlentities($feeds->channel->item[$pos]->link);
                        echo "\" onclick=\"target='_blank';\" title=\"";
                        echo htmlentities(getCleanTitle($feeds->channel->item[$pos]->title));
                        echo "\">";
                        echo "<img class=\"widget_image\" src=\"";
                        echo getImageFromFeed($feeds->channel->item[$pos]);
                        echo "\" alt=\"Music News Feed Image\" />";
                        echo "</a>";
                    }
                ?>
            </div>
        </div>
    <?php
    }

    /**
     * Returns an array of numbers shuffled
     * from 0-49, this will shuffle the articles
     * so they appear to change on each page.
     * Although this widget is labelled suggested
     * articles it does not state why they are suggested
     * in this case, they are suggested at random.
     * However, if this section was in the brief
     * other avenues and algorithms could be explored.
     * However, for this project it is unneeded complexity.
     *
     * @return array : returns array of numbers
     */
    private function getArrayPositions()
    {
        $numbers = array();
        for($i = 0; $i<50; $i++)
        {
            $numbers[$i] = $i;
        }
        shuffle($numbers);
        return array_slice($numbers,0,9);
    }

}