<?php
/**
 * This class creates a button that is preformatted to match
 * the site's look and feel
 * @author: Graham Coulby
 * @version: 31/01/2015
 */

class Button
{
    function __construct($buttonText)
    {
        ?>
        <div class="button">
            <span>
                <?php echo $buttonText ?>
            </span>
        </div>
        <?php
    }
}