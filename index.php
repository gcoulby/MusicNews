<?php
/**
 * 
 * @author: Coulby
 * @version: 04/03/2015
 */

include_once 'includes/includes.php';
/**
 * This class calls its superclasses automatically
 * but is required to implement the abstract method.
 * The abstract method bodyContent() determines what
 * comes between the common header and footer.
 */
class Index extends PublicPage
{
    /**
     * This is where HTML and php are put
     * The contents of this method are displayed
     * between the header and the footer
     */
    protected function bodyContent()
    {
        /**
         * Builds a new FeedPage object, which increments
         * through the press releases in an RSS feed and
         * creates a new FeedItem object for each press release
         */
        new FeedPage("http://www.music-news.com/rss/news.asp");
    }
}
new Index(null,false);