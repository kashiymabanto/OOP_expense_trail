<?php
session_start();

// Include required classes
include("db/connection.php");
include("db/crud.php");

// User class for handling the sign-up process
class UserCon {
    private $crud;

    public function __construct($crud) {
        $this->crud = $crud;
    }

    // Method to register a new user
    public function register($name, $username, $password) {
        // Insert the user into the database
        return $this->crud->signup($name, $username, $password);
    }
}

// Instantiate the Database and CRUD class
$db = new Database();
$con = $db->getConnection();
$crud = new user($con);

// Instantiate the User class
$user = new UserCon($crud);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['signup'])) {
        $name = $_POST['name'];
        $username = $_POST['user'];
        $password = $_POST['pass'];

        // Call the register method
        if ($user->register($name, $username, $password)) {
            $_SESSION['status1'] = "Registration successful. Please login!";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['status'] = "Registration failed. Try again!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="css/signup.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EXPENSE</title>
</head>

<body>
    <h1>Expense</h1>
    <div class="signup-form">
        <h2>Expense Sign Up</h2>

        <!-- Show status messages -->
        <?php if (isset($_SESSION['status'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['status']; unset($_SESSION['status']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['status1'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['status1']; unset($_SESSION['status1']); ?>
            </div>
        <?php endif; ?>

        <form action="signup.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Name" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="user" placeholder="Username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="pass" placeholder="Password" required>

            <button type="submit" name="signup">SIGN UP</button>
        </form>

        <p>Already have an account? 
            <a href="index.php">Login here</a>
        </p>
    </div>
</body>

</html>
