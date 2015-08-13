<?php
/**
 *
 * @author: Coulby
 * @version: 04/03/2015
 */

include_once 'includes/includes.php';

class TestFeed extends PublicPage
{
    protected function bodyContent()
    {
        new FeedPage("http://www.music-news.com/rss/reviews.asp");
    }
}
new TestFeed(null,false);