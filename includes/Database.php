<?php
/**
 * Rather than just including a file with a pdo object in
 * I have created my own Database object which houses
 * the PDO object. This abstracts the PDO abstraction
 * one further level, this coupled with the abstraction
 * of the database credentials in the DbConfig class
 * means that this database has triple abstraction.
 * This Database class requires the dbConfig.php file
 * to run as per the assignment brief.
 */

require_once "dbConfig.php";
class Database
{
    private $hostname;
    private $db_name;
    private $username;
    private $password;
    private $dbsn;
    private $db;
    private $error;
    private $seed;
    private $seed2;

    /**
     * This constructor builds the connection between
     * the DbConfig file and the newly instantiated PDO
     * object. It surrounds the the PDO instantiation
     * in a try catch block as per the recommendation of
     * the PHP documentation.
     */
    function __construct()
    {
        $dbConfig = new DbConfig();
        $this->hostname = $dbConfig->get_hostname();
        $this->db_name = $dbConfig->get_db_name();
        $this->username = $dbConfig->get_username();
        $this->password = $dbConfig->get_password();
        $this->seed = $dbConfig->get_seed();
        $this->seed2 = $dbConfig->get_seed2();
        $this->dbsn = "mysql:host={$this->hostname};dbname={$this->db_name}";
        try
        {
            $this->db = new PDO($this->dbsn, $this->username, $this->password);
        } catch (Exception $e)
        {
            echo "There was an error";
            $this->error = $e->getMessage();
        }
    }

//    /**
//     * This method is for testing purposes
//     * simply echos the tables in a database
//     * to ensure the database connectivity is
//     * working.
//     */
//    function test_connection()
//    {
//        if($this->db)
//        {
//            $sql = 'SHOW TABLES';
//            $stmt = $this->db->query($sql);
//            echo print_r($stmt->fetchAll(PDO::FETCH_COLUMN));
//            echo "<p>CONNECTION SUCCESSFUL</p>";
//        }
//        else
//        {
//            echo "<p>$this->error</p>";
//        }
//    }

//    /**
//     * This is another testing method
//     * takes complete SQL statement
//     * and executes it through PDO functions
//     * this is used to build methods and
//     * ensure syntax is correct, it will
//     * not be used in the final product
//     * @param $sql String : the SQL to execute.
//     */
//    function sql_scratchPad($sql)
//    {
//        $stmt = $this->db->query($sql);
//        echo print_r($stmt->fetchAll(PDO::FETCH_COLUMN));
//    }

    /**
     * @return PDO $db : This returns the database
     * PDO object abstracted by this object.
     */
    function getDb()
    {
        return $this->db;
    }

    /**
     * Does what it says on the tin.... with a few
     * little nuances to make it personal.
     * First the input is cleaned prevent errors and/or
     * SQL injection then the password is hashed using
     * my hashing algorithm hashPass(); before being
     * queried through the PDO functions.
     *
     * @param $username String : the username to validate
     * @param $password String : the password to validate
     * @param $table String : the table to search in
     * @return bool : Returns true if valid
     */
    function validate_login_credentials($username, $password, $table)
    {
        $username = $this->clean_input($username);
        $password = $this->clean_input($password);
        $password = $this->hashPass($password);
        if($this->db)
        {
            $sql = "SELECT * FROM {$table} WHERE `email`='{$username}' AND `password`='{$password}'";
            $stmt = $this->db->query($sql);
            return (count($stmt->fetchAll(PDO::FETCH_ASSOC)) == 1) ? true : false;
        }
    }

    /**
     * This method allows for multiple conditions to be passed
     * in with a query
     * @param $table : The table to query
     * @param $condition : The condition that needs to be met
     * for example SELECT * FROM 'cp_users' WHERE userID = 1;
     * @return array : An array of rows
     */
    function find_all_from_table($table, $condition)
    {
        $users = array();

        if($this->db)
        {
            $sql = "SELECT * FROM {$table}"; //Select all rows from the table
            if(!is_null($condition))
            {
                $sql .= " " . $condition; //Concatenate the condition if it is not null
            }
            $stmt = $this->db->query($sql);
            $rows = $stmt->fetchAll();

            /**
             * This foreach loop overcomes an intrinsic problem
             * with PDO objects. PDO objects when returning a fetch all
             * return not only the column names but a bunch of numeric
             * keys which do not correspond to the datebase. This foreach
             * loop builds an array of rows where the key => value pair
             * corresponds to the column => field pair in the database.
             */
            foreach($rows as $key => $row)
            {
                foreach($row as $row_key => $value)
                {
                    if(is_int($row_key))
                    {
                        unset($row[$row_key]);
                    }
                }
                $users[] = $row;
            }
            return $users;
        }
    }

    /**
     * Unlike find all from the database this method
     * is designed to return ONE row. It still strips
     * the numeric Keys from the PDO query and builds
     * up a clean array of column => field pairs returning
     * just one single row that matches the condition.
     *
     * @param $table String : the table to search in
     * @param $column String : the column to query
     * @param $field String : the field to query
     * @return mixed|PDOStatement : Returns a row from the database
     */
    function check_table_for_match($table, $column, $field)
    {
        if($this->db)
        {
            $sql = "SELECT * FROM {$table} WHERE {$column}='{$field}'";
            $stmt = $this->db->query($sql);
            $stmt = $stmt->fetch();
            foreach($stmt as $key => $value)
            {
                if(is_int($key))
                {
                    unset($stmt[$key]);
                }
            }
            return $stmt;
        }
    }

    /**
     * @param $subscriberID String : The subscriberID of to associate with the article
     * @param $link String : The hyperlink where the full article is found
     * @param $title String : The title of the article
     * @param $description String : The description (text body) of article.
     * @param $image String : The src of the image
     * @param $datePublished String : The date in which the article was first published
     * @return bool : Returns true if the article was saved to the database.
     */
    function save_article_to_database($subscriberID, $link, $title, $description, $image, $datePublished)
    {
        $saved_date = date("Y-m-d"); //Today's date

        $sql = "INSERT INTO  saved_press_releases(
                pressReleaseID ,
                subscriberID ,
                link ,
                title ,
                description ,
                image,
                dateSaved,
                datePublished
                )
                VALUES (
                NULL ,
                :subscriberID ,
                :link ,
                :title ,
                :description ,
                :image,
                :dateSaved,
                :datePublished
                )";
        $stmt = $this->db->prepare($sql);
        /**
         * To prevent SQL injection I have chosen to use placeholder variables
         * in the SQL query, I then use PDO function bindParam() to bind
         * the parameters passed into the method to the placeholder variable
         * whilst at the same time ensuring that the value passed in matches
         * the value that each column in the database structure expects.
         */
        $stmt->bindParam(':subscriberID', $subscriberID, PDO::PARAM_INT, 8);
        $stmt->bindParam(':link', $link, PDO::PARAM_STR, 255);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR, 255);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':image',  $image, PDO::PARAM_STR, 255);
        $stmt->bindParam(':dateSaved', $saved_date);
        $stmt->bindParam(':datePublished', $datePublished, PDO::PARAM_STR, 45);
        return $stmt->execute();
    }

    /**
     * Updates the row of one subscriber to include the date/time
     * that the user logged in. This will be displayed on the site
     * the NEXT time the user logs in.
     *
     * @param $id INT : The ID of the current subscriber logging in
     * @return bool : returns true if the row is updated
     */
    function setLastLogin($id)
    {
        $date = date("Y-m-d H:i:s");
        $sql = "UPDATE subscriber SET lastlogin = '{$date}' WHERE subscriberID = {$id}";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }

    /**
     * Deletes row where row ID = $id
     *
     * @param $table String : The table to search in
     * @param $column String : the column to query (most commonly unique primary key)
     * @param $id INT : the unique ID to query
     * @return bool : Returns true if row is deleted
     */
    function delete_row($table, $column, $id)
    {
        $sql = "DELETE FROM " . $table . " WHERE " . $column . " = " . $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }

    /**
     * @param $table String : The table to search in
     * @param $column1 String : The first column to query (eq. rowID)
     * @param $id1 INT : The ID for column1
     * @param $column2 String : The second row to query (foreign key e.g. userID)
     * @param $id2 INT : The ID for column2
     * @return bool : Returns true if row is deleted.
     */
    function delete_row_by_two_conditions($table, $column1, $id1, $column2, $id2)
    {
        $sql = "DELETE FROM " . $table . " WHERE " . $column1 . " = " . $id1 . " AND " . $column2 . " = " . $id2;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }

    /**
     * This is my hashing algorithm I mentioned earlier
     * This method works in tandem with splitString()
     * The password and two seeds are split exactly in
     * half (unless the length of the password is odd,
     * then the second half will be 1 char bigger).
     * The order goes:
     *   1st half of seed one
     * + 1st half of password
     * + 2nd half of seed one
     * + 1st half of seed two
     * + 2nd half of password
     * + 2nd half of seed two
     *
     * This password is then hashed using SHA1 as per
     * the assignment criteria. However, for more security
     * this can be commented out and the commented out
     * function can be used which provides SHA256 encryption
     * a much more secure encryption. Although this hash() function
     * also allows encryption in over 10 different encryption (php.net, 2015)
     *
     * @param $password String : The password to hash
     * @return string : Returns the hashed password
     */
    function hashPass($password)
    {
        $passwordArr = $this->splitString($password);
        $seedOneArr = $this->splitString($this->seed);
        $seedTwoArr = $this->splitString($this->seed2);
        return hash("sha1",$seedOneArr[0].$passwordArr[0].$seedOneArr[1].$seedTwoArr[0].$passwordArr[1].$seedTwoArr[1],false);
//        return hash("sha256",$seedOneArr[0].$passwordArr[0].$seedOneArr[1].$seedTwoArr[0].$passwordArr[1].$seedTwoArr[1],false); // This return would be for more secure encryption
    }

    /**
     * Splits the string exactly in half unless the
     * string's length is odd, then the string's
     * second half is one char longer.
     *
     * @param $string String : The string to split
     * @return array : returns an array with both halves of the split
     */
    function splitString($string)
    {
        $split = str_split($string,strlen($string)/2);

        if(sizeof($split)>2)
        {
            $split[1] .= $split[2];
            unset($split[2]);
        }
        return $split;
    }

    /**
     * @param $input String : the string passed by the user
     * @return string : Returns a cleaned string to prevent SQL injection.
     */
    function clean_input($input)
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlentities($input);
        return $input;
    }
}