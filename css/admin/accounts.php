<?php
session_start();

// Include the required classes
include("../db/connection.php"); // Includes the database connection class
include("../db/function.php");   // Includes additional functions if needed
include("../db/user.php"); // Includes the User class for authentication
include("../db/crud.php"); 


// Auth class for user authentication
class Authcon {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Check if user is logged in
    public function checkLogin() {
        if (!isset($_SESSION['id'])) {
            header("Location: ../signout.php");
            exit();
        }

        $userId = $_SESSION['id'];
        $query = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc(); // Return user data
    }

    // Check if the user has the required permissions
    public function hasRequiredPermissions($userData) {
        return $userData['user_type'] === '2'; // Check if the user type is '2'
    }
}

$expense = new user($con);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission to add an account
    if (isset($_POST['account'])) {
        $name = $_POST['name'];
        if ($expense->addAccount($name)) {
            $_SESSION['status1'] = "Account added successfully.";
        } else {
            $_SESSION['status'] = "Failed to add account.";
        }
    }
}

// Main script
$db = new Database();
$con = $db->getConnection();  // Get the database connection

// Instantiate Auth class and check the login
$auth = new Auth($con);
$user_data = $auth->checkLogin(); // Calls the checkLogin() method to verify user login status

// Check if the user has the required type (user_type '2')
if ($user_data['user_type'] !== '2') {
    header("Location: ../signout.php");
    exit(); // Stop further script execution
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="../css/bootstrap/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/accounts.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petty Cash</title>
</head>

<body>
<?php 
include("../sidebar/admin-navbar.php")
?>
    <div class="container mt-5 pt-2">
        <div class="container mt-5 pt-5">
            <?php
            if (isset($_SESSION['status'])) {
            ?>
                <div class="text-center">
                    <div class="alert alert-danger alert-dismissible fade show mx-auto" role="alert" style="width: 300px; border-radius: 20px;">
                        <strong> Hey! </strong> <?php echo $_SESSION['status']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            <?php

                unset($_SESSION['status']);
            }

            if (isset($_SESSION['status1'])) {
            ?>
                <div class="text-center">
                    <div class="alert alert-success alert-dismissible fade show mx-auto" role="alert" style="width: 300px; border-radius: 20px;">
                        <strong> Hey! </strong> <?php echo $_SESSION['status1']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            <?php

                unset($_SESSION['status1']);
            }
            ?>
        </div>
        <div class="container-fluid d-flex justify-content-center petty_cash">
            <div class="box border border-dark rounded-2 w-25">
                <h1 class="text-center mt-3">Accounts</h1>
                <div class="container">
                    <div class="row">
                    <form action="accounts.php" method="POST">
                            <div class="col-md-12 mt-3">
                                <label for="inputUsername" class="form-label"><b>Expense Account</b></label>
                                <input type="text" name="name" class="form-control" placeholder="Account">
                            </div>
                            <div class="col-md-12 mt-4 d-flex justify-content-center">
                                <center>
                                    <button type="submit" name="account" class="btn btn-warning w-50">Add</button>
                                </center>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>




</body>

</html>