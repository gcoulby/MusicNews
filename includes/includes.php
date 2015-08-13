<?php
/**
 * This file will link all of the files together
 * with one include similar to the way a Java
 * build path works... only far less efficient
 */

mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');

require_once 'functions.php';
require_once 'Database.php';
require_once 'Widget.php';
require_once 'FeedItem.php';
require_once 'SavedArticle.php';
require_once 'FeedPage.php';
require_once 'PopularArticlesWidget.php';
require_once 'Page.php';
require_once 'AdminPage.php';
require_once 'PublicPage.php';

