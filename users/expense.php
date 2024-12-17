<?php
session_start();

// Include the required files
include("../db/connection.php");
include("../db/function.php"); // Includes User.php indirectly
include("Controller/UserController.php");
include("../db/crud.php");

// Initialize Database connection
$db = new Database();
$con = $db->getConnection();

// Check if the connection is successful
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize Auth class and check login status
$auth = new Auth($con);
$user_data = $auth->checkLogin();  // This checks the login using the Auth class

// Ensure the user is an admin (you can adjust this based on user type)
if ($user_data['user_type'] !== '1') {
    header("Location: ../signout.php");
    exit();
}

// Instantiate the User class
$userObj = new User($con);

// Initialize UserController with the User object
$userController = new UserController($userObj);

// Handle form submissions for adding petty cash
if (isset($_POST['pay'])) {
    $data = [
        'expense' => $_POST['expense'],
        'cash' => $_POST['cash'],
        'date' => $_POST['date'],
        'id' => $_POST['id']
    ];
    $userController->handleAddExpense($data);
}

class ExpenseAccountHandler {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getExpenseAccounts() {
        $query = "SELECT * FROM expense_account";
        $result = $this->db->query($query);
        return $result; // Return result set
    }
}

// Instantiate the ExpenseAccountHandler class and fetch expense account records
$expenseAccountHandler = new ExpenseAccountHandler($con);
$expenseAccounts = $expenseAccountHandler->getExpenseAccounts();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../css/expense.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense</title>
</head>

<body>
    <?php
    include("../sidebar/navbar.php");
    ?>

    <div class="container mt-5">
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
                <h1 class="text-center mt-3">Petty Cash</h1>
                <div class="container">
                    <div class="row">
                        <form action="expense.php" method="POST">
                            <div class="col-md-12">
                                <input type="hidden" value="<?= $user_data['id']; ?>" name="id">
                                <label for="inputUsername" class="form-label"><b>Date</b></label>
                                <input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-12 mt-3">
                                <label for="inputState" class="form-label"><b>Expense Account</b></label>
                                <select id="inputState" class="form-select" name="expense">
                                    <option selected>Choose...</option>
                                    <?php
                                    foreach ($expenseAccounts as $row) {
                                    ?>
                                        <option value="<?= $row['name'] ?>"><?= $row['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-12 mt-3">
                                <label for="inputUsername" class="form-label"><b>Petty Cash</b></label>
                                <input type="text" name="cash" class="form-control" placeholder="Amount" required>
                            </div>
                            <div class="col-md-12 mt-3 d-flex justify-content-center">
                                <button type="submit" class="btn btn-warning w-50" name="pay">Add</button>
                            </div>
                        </form>
                        </br>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
