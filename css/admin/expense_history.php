<?php
session_start();
include("../db/connection.php");
include("../db/function.php");
include("../db/user.php"); // Include the User class

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

// Transaction class to handle transaction-related operations
class Transaction {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Fetch transactions for users with user_type '1'
    public function getTransactions() {
        $sql = "SELECT * FROM transaction INNER JOIN users ON transaction.users_id = users.id WHERE user_type = '1'";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);  // Fetch all transactions as an associative array
    }
}

// Main script logic
$db = new Database();
$con = $db->getConnection();  // Get the database connection

// Instantiate Auth class and check the login
$auth = new Auth($con);
$user_data = $auth->checkLogin();  // Calls the checkLogin() method to verify user login status

// Check if the user has the required type (user_type '2')
$user_data = $auth->checkLogin(); // Calls the checkLogin() method to verify user login status
if ($user_data['user_type'] !== '2') {
    header("Location: ../signout.php");
    exit(); // Stop further script execution
}

// Fetch transactions for users with user_type '1'
$transaction = new Transaction($con);
$transactions = $transaction->getTransactions();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="../css//index-admin.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - home</title>
</head>

<body>
    <?php
    $page = 'history';
    include("../sidebar/admin-navbar.php");
    ?>
    <main>
        <div class="container-fluid mt-4">
            <div class="container d-flex justify-content-between">
                <h2 class="pt-3">Users Transaction</h2>
            </div>
            <div class="container table-borrwed mt-5">
                <table class="table table-striped mb-5 w-75 mx-auto table-bordered">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Expense</th>
                            <th>Amount</th>
                            <th>Date</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($transactions as $row) {
                        ?>
                            <tr>
                                <td><?= $row['id']; ?>
                                <td><?= $row['name']; ?>
                                <td><?= $row['expense']; ?></td>
                                <td><?= $row['amount']; ?></td>
                                <td><?= $row['date']; ?></td>
                            </tr>

                        <?php } ?>
                </table>
            </div>
        </div>
    </main>





</body>

</html>