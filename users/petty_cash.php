<?php
session_start();
include("../db/connection.php"); // Includes the database connection file
include("../db/function.php");   // Includes additional functions from the function.php file
include("../db/user.php");       // Includes the User class
// Auth class to handle user login and permission checks
class Authcon {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

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

    public function hasPermission($userType) {
        return $userType === '1'; // Check if user has admin rights (user_type '1')
    }
}

// CashHistoryHandler class to fetch history and calculate total cash
class CashHistoryHandler {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getCashHistory() {
        $query = "SELECT * FROM history_cash";
        $result = $this->db->query($query);
        return $result;
    }

    public function getTotalCash() {
        $query = "SELECT sum(cash) as total FROM petty_cash";
        $result = $this->db->query($query);
        $totalCash = 0;

        if ($row = $result->fetch_assoc()) {
            $totalCash = $row['total']; // Get total cash value
        }

        return $totalCash;
    }
}

// Instantiate the Database and Auth classes
$db = new Database();
$con = $db->getConnection();

// Instantiate the Auth class and check the login status
$auth = new Auth($con);
$user_data = $auth->checkLogin(); // Calls the checkLogin() method of the Auth class to verify user login status

// Verify the user's type to ensure they have the appropriate permissions
if ($user_data['user_type'] !== '1') {
    header("Location: ../signout.php");
    exit(); // Stop further script execution
}

// Instantiate the CashHistoryHandler class and fetch data
$cashHistoryHandler = new CashHistoryHandler($con);
$cashHistory = $cashHistoryHandler->getCashHistory();
$totalCash = $cashHistoryHandler->getTotalCash();

// Now, you can use $cashHistory and $totalCash to display the fetched records or process them further.
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../css/petty_cash.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petty Cash</title>
</head>

<body>
   <?php 
   include("../sidebar/navbar.php");
   ?>
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
    <div class="container mt-5 pt-2">
        <div class="container mt-5 pt-5">

        </div>
        <div class="container">
            <div class="row">
                
                </div>
                <div class="container petty_cash mt-4 col-md-6 mb-4">
                    <div class="box2 border border-dark rounded-2 w-75 ">
                        <h1 class="text-center mt-3">Petty Cash</h1>
                        <div class="container">
                            <h6>Total: ₱<?php echo $totalCash; ?></h6>
                            <hr>
                            <div class="container d-flex justify-content-between w-75">
                                <span>Date</span>
                                <span>Cash</span>
                            </div>
                            <hr>
                            <!-- Display the history records -->
                            <?php if ($cashHistory && mysqli_num_rows($cashHistory) > 0) { ?>
                                <?php while ($row = mysqli_fetch_assoc($cashHistory)) { ?>
                                    <div class="container d-flex justify-content-between w-75">
                                        <span><?= $row['date'] ?></span>
                                        <span><?= $row['cash'] ?></span>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <span>No records found.</span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
