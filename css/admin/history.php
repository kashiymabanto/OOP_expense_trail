<?php
include("../db/connection.php"); // Includes the database connection class
include("../db/function.php");   // Includes additional functions
include("../db/filter.php");     // Includes filtering functions
include("../db/user.php"); // Includes the User class

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

    <link rel="stylesheet" href="../css/history.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petty Cash</title>
</head>

<body>
    <?php
    include("../sidebar/admin-navbar.php")
    ?>
    <div class="container mt-5 pt-1">
        <div class="row">
            <div class="container d-flex justify-content-center petty_cash mt-4 col-md-6">
                <div class="box border border-dark rounded-2 w-50">
                    <h1 class="text-center mt-3">History</h1>
                    <div class="container">
                        <div class="row">
                            <form method="POST">
                                <div class="col-md-12 mt-3">
                                    <input type="hidden" name="id" value="<?= $user_data['id']; ?>">
                                    <label for="inputUsername" class="form-label"><b>Date Start</b></label>
                                    <input type="date" name="date_start" class="form-control" required>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label for="inputUsername" class="form-label"><b>Date End</b></label>
                                    <input type="date" name="date_end" class="form-control" required>
                                </div>
                                <div class="col-md-12 mt-4 d-flex justify-content-center">
                                    <button type="submit" name="search" class="btn btn-warning w-50">Search</button>
                                </div>
                                <br><br>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container petty_cash mt-4 col-md-6">
                <div class="box2 border border-dark rounded-2 mx-auto">
                    <h1 class="text-center mt-3">Expense Trail</h1>
                    <div class="container">
                        <hr>
                        <?php if ($result && mysqli_num_rows($result) > 0) {   ?>
                            <table class="table table-borderless mx-auto">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Expense</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    while ($row = mysqli_fetch_assoc($result)) { 
                                        ?>
                                        <tr>
                                        <td><?= $row['name'] ?></td>
                                            <td><?= $row['expense'] ?></td>
                                            <td><?= $row['date'] ?></td>
                                            <td>₱<?= $row['amount'] ?></td>
                                        </tr>
                                    <?php } ?>
                                    
                                    <h4 class="text-end me-5">Total: <b>₱<?php echo $output ?></b></h4>
                                    <a href="../edit/generate_pdf.php?date_start=<?= $_POST['date_start']; ?>&date_end=<?= $_POST['date_end']; ?>" class="btn btn-primary" target="_blank">Print</a>
                                    <hr>


                                <?php } else { ?>
                                    <span>No records found for the selected date range.</span>
                                <?php } ?>

                                </tbody>
                            </table>

                    </div>
                </div>
            </div>
        </div>
    </div>




</body>

</html>