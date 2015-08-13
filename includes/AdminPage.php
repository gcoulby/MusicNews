<?php

include_once 'includes/includes.php';

/**
 * Class AdminPage
 * This method is part of my design pattern and
 * would be used as an extention to this site,
 * if the site required an admin section in the future
 * this would allow for the site to have a different look
 * and feel to the rest of the site and would allow
 * different access levels to be put in place without
 * accessing the $_SERVER super global
 */
abstract class AdminPage extends Page
{

    public function __construct($authors, $arrayOfStyles)
    {

        $this->stylesheet = 'admin_style.css';
        parent::__construct($authors, $arrayOfStyles, false, true);
        $this->bodyContent();
        $this->insertFooter($authors);
    }

    protected function openBody($show_widget)
    {
        ?>

        <?php
    }

    public abstract function bodyContent();


    protected function insertFooter()
    {
     ?>

    <?php
    }

}