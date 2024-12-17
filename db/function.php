<?php
// Include the required classes
include_once 'user.php'; // Ensure User class is included
include_once 'connection.php'; // Database connection

class Auth {
    private $con;
    private $user;

    public function __construct($dbConnection) {
        $this->con = $dbConnection;
        $this->user = new UserCon($this->con); // Initialize the User class
    }

    // Method to check if the user is logged in
    public function checkLogin() {
        if (isset($_SESSION['id'])) {
            $id = $_SESSION['id'];
            return $this->user->checkLogin($id); // Call checkLogin method of User class
        }

        header("Location: ../index.php"); // Redirect if user is not logged in
        die;
    }
}
?>
