<?php
/**
 *
 * @author: Coulby
 * @version: 04/03/2015
 */

include_once 'includes/includes.php';

class SearchPage extends PublicPage
{
    function bodyContent()
    {
        new FeedPage("http://www.music-news.com/rss/news.asp");
    }
}
new SearchPage(null,false);