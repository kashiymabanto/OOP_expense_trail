<?php
session_start();
include("../db/connection.php"); // Includes the database connection file to connect to the database
include("../db/function.php");   // Includes additional functions from the function.php file
include("../db/user.php"); // Include the User class

// TransactionHandler class for handling transaction-related operations
class TransactionHandler
{
    private $con;
    private $userId;

    public function __construct($con, $userId)
    {
        $this->con = $con;
        $this->userId = $userId;
    }

    // Fetch all transactions for the current user
    public function getTransactions()
    {
        $sql = "SELECT * FROM transaction WHERE users_id = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $this->userId);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Fetch the total amount of transactions for the current user
    public function getTotalAmount()
    {
        $sql = "SELECT SUM(amount) AS total FROM transaction WHERE users_id = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $this->userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}

// Auth class for user authentication
class Authcon
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    // Check if the user is logged in and return user data
    public function checkLogin()
    {
        if (!isset($_SESSION['id'])) {
            header("Location: ../signout.php");
            exit();
        }

        // Query to fetch user data
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $_SESSION['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}

// Instantiate the Database class and get the connection
$db = new Database();
$con = $db->getConnection();

// Check if the connection is successful
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Instantiate the Auth class and check the login
$auth = new Auth($con);
$user_data = $auth->checkLogin(); // Calls the checkLogin() method of the Auth class to verify user login status

// Verify the user's type to ensure they have the appropriate permissions
if ($user_data['user_type'] !== '1') {
    header("Location: ../signout.php");
    exit(); // Stop further script execution
}

// Instantiate the TransactionHandler class for transaction-related operations
$transactionHandler = new TransactionHandler($con, $_SESSION['id']);
$transactions = $transactionHandler->getTransactions(); // Get all transactions
$output = $transactionHandler->getTotalAmount(); // Get total transaction amount
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- <link href="../css/bootstrap/bootstrap.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="../css/expense-list.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense</title>
</head>

<body>
<?php 
include("../sidebar/navbar.php")
?>
    <div class="container petty_cash mt-5">
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

        <div class="container border border-dark rounded w-75">
            <h1 class="d-flex justify-content-center">Expense List</h1>
            <h3>Total: ₱<?php echo $output ?></h3>
            <div class="container-fluid d-flex justify-content-center">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Expense Account</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($transactions as $row) {
                        ?>
                            <tr>
                                <th><?= $row['date'] ?></th>
                                <td><?= $row['expense'] ?></td>
                                <td><?= $row['amount'] ?></td>
                                <td>
                                <a href="../edit/edit_expense.php?id=<?= $row['id'] ?>" class="btn btn-success">Edit</a>
                                <a href="../edit/confirm_delete.php?id=<?= $row['id'] ?>" class="btn btn-danger">Delete</a>


                                </td>
                            </tr>




                            <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>




</body>

</html>