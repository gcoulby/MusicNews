<?php

/**
 * Class DbConfig
 * This class works as an even further level
 * of abstraction from the database. I chose
 * to not simply include a file of variables
 * (like the assignment asks for) as I deem
 * this to be insecure. Instead I have chosen
 * to create a dbconfig Class and use private
 * variables with public getters that can be
 * used within the database object. This means
 * that not even the database class knows what
 * these variables are.... only how to access them.
 */
class DbConfig
{
    private $hostname = "localhost";
    private $db_name = "";
    private $username = "";
    private $password = "";
    private $seed = '<.Lij)(-=#';
    private $seed2 = 'X3r5(!-+=~';

    function __construct()
    {
        $this->password ="";
    }


    function get_hostname()
    {
        return $this->hostname;
    }

    function get_db_name()
    {
        return $this->db_name;
    }

    function get_username()
    {
        return $this->username;
    }

    function get_password()
    {
        return $this->password;
    }

    function get_seed()
    {
        return $this->seed;
    }

    function get_seed2()
    {
        return $this->seed2;
    }
}
