<?php
// user.php file
if (!class_exists('UserCon')) {
    class UserCon {
        private $con;

        public function __construct($dbConnection) {
            $this->con = $dbConnection;
        }

        public function login($username, $password) {
            $sql = "SELECT * FROM users WHERE `username` = ? AND `password` = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($result->num_rows > 0) {
                $_SESSION['id'] = $user['id'];
                if ($user['user_type'] == 1) {
                    header("Location: ../users/petty_cash.php");
                } else if ($user['user_type'] == 2) {
                    header("Location: ../admin/index.php");
                }
                exit();
            } else {
                $_SESSION['status'] = "Wrong username or password!";
                header('location: ../index.php');
                exit();
            }
        }

        // Method to check if user is logged in
        public function checkLogin($id) {
            $sql = "SELECT * FROM users WHERE id = ? LIMIT 1";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                return $result->fetch_assoc(); // Return user data
            }

            header("Location: ../index.php");
            die;
        }
    }
}
?>
