<?php
session_start(); // Start the session to manage user login state

include_once 'connection.php'; // Include the database connection class
include_once 'User.php'; // Include the User class

class LoginHandler
{
    private $user;
    private $username;
    private $password;

    public function __construct()
    {
        $database = new Database(); // Create a new Database object
        $connection = $database->getConnection(); // Get the connection
        $this->user = new UserCon($connection); // Create a new User object
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
            $this->username = $_POST['user'];
            $this->password = $_POST['pass'];
            $this->processLogin();
        }
    }

    private function processLogin()
    {
        $this->user->login($this->username, $this->password);
    }
}

// Instantiate the LoginHandler and process the request
$loginHandler = new LoginHandler();
$loginHandler->handleRequest();
?>
