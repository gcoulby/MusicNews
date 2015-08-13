<?php

include_once "Widget.php";

/**
 * Class PopularArticlesWidget
 * This class instantiates the Widget class
 * within a div which will be styled into place.
 */
class PopularArticlesWidget extends Widget
{
    function __construct()
    {
        ?>
            <div class="widget">
                <h2 class="widget_title">Popular Articles</h2>
            </div>
        <?php
    }
}