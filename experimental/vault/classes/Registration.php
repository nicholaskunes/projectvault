<?php

/**
 * Class registration
 * handles the user registration
 */
class Registration
{
    /**
     * @var object $db_connection The database connection
     */
    private $db_connection = null;
    /**
     * @var array $errors Collection of error messages
     */
    public $errors = array();
    /**
     * @var array $messages Collection of success / neutral messages
     */
    public $messages = array();

    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$registration = new Registration();"
     */
    public function __construct()
    {
        if (isset($_POST["register"])) {
            $this->registerNewUser();
        }
    }

    /**
     * handles the entire registration process. checks all error possibilities
     * and creates a new user in the database if everything is fine
     */
    private function registerNewUser()
    {
        if (empty($_POST['first'])) {
            echo "Empty Username";
        } elseif (empty($_POST['passwd']) || empty($_POST['passwd2'])) {
            echo "Empty Password";
        } elseif ($_POST['passwd'] !== $_POST['passwd2']) {
            echo "Password and password repeat are not the same";
        } elseif (strlen($_POST['passwd']) < 6) {
            echo "Password has a minimum length of 6 characters";
        } elseif (strlen($_POST['first']) > 64 || strlen($_POST['first']) < 2) {
            echo "Username cannot be shorter than 2 or longer than 64 characters";
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['first'])) {
            echo "Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters";
        } elseif (empty($_POST['email'])) {
            echo "Email cannot be empty";
        } elseif (strlen($_POST['email']) > 64) {
            echo "Email cannot be longer than 64 characters";
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            echo "Your email address is not in a valid email format";
        } elseif (!empty($_POST['first'])
            && strlen($_POST['first']) <= 64
            && strlen($_POST['first']) >= 2
            && preg_match('/^[a-z\d]{2,64}$/i', $_POST['first'])
            && !empty($_POST['email'])
            && strlen($_POST['email']) <= 64
            && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
            && !empty($_POST['passwd'])
            && !empty($_POST['passwd2'])
            && ($_POST['passwd'] === $_POST['passwd2'])
        ) {
            // create a database connection
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            // change character set to utf8 and check it
            if (!$this->db_connection->set_charset("utf8")) {
                echo $this->db_connection->error;
            }

            // if no connection errors (= working database connection)
            if (!$this->db_connection->connect_errno) {

                // escaping, additionally removing everything that could be (html/javascript-) code
                $first = $this->db_connection->real_escape_string(strip_tags($_POST['first'], ENT_QUOTES));
                $email = $this->db_connection->real_escape_string(strip_tags($_POST['email'], ENT_QUOTES));

                $user_password = $_POST['passwd'];

                // crypt the user's password with PHP 5.5's password_hash() function, results in a 60 character
                // hash string. the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using
                // PHP 5.3/5.4, by the password hashing compatibility library
                $passwdhash = password_hash($user_password, PASSWORD_DEFAULT);

                // check if user or email address already exists
                $sql = "SELECT * FROM users WHERE first = '" . $first . "' OR email = '" . $email . "';";
                $query_check_user_name = $this->db_connection->query($sql);

                if ($query_check_user_name->num_rows == 1) {
                    echo "Sorry, that username / email address is already taken.";
                } else {
                    // write new user's data into database
                    $sql = "INSERT INTO users (first, passwdhash, email)
                            VALUES('" . $first . "', '" . $passwdhash . "', '" . $email . "');";
                    $query_new_user_insert = $this->db_connection->query($sql);

                    // if user has been added successfully
                    if ($query_new_user_insert) {
                        $this->messages[] = "Your account has been created successfully. You can now log in.";
                    } else {
                        echo "Sorry, your registration failed. Please go back and try again.";
                    }
                }
            } else {
                echo "Sorry, no database connection.";
            }
        } else {
            echo "An unknown error occurred.";
        }
    }
}
