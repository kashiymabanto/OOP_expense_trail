<?php

include("../db/connection.php"); // Includes the database connection file to establish a connection to the database
include("../db/function.php");   // Includes additional functions from the function.php file
include("../db/filter.php");     // Includes filtering functions to sanitize or validate input data
include("../db/user.php"); // Include the User class

// Instantiate the Database class and get the connection
$db = new Database();  
$con = $db->getConnection();  // Get the database connection

// Check if the connection is successful
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$auth = new Auth($con);
$user_data = $auth->checkLogin(); // Calls the checkLogin() method of the Auth class to verify user login status

// Verify the user's type to ensure they have the appropriate permissions
if ($user_data['user_type'] !== '1') {
    // If the user is not of the required type (user_type '1'), redirect to the signout page
    // This ensures that only users with the appropriate permissions can access the page
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
    include("../sidebar/navbar.php")
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
                <div class="box2 border border-dark rounded-2 w-75 mx-auto">
                    <h1 class="text-center mt-3">Expense Trail</h1>
                    <div class="container">
                        <hr>
                        <?php if ($result && mysqli_num_rows($result) > 0) {   ?>
                            <table class="table table-borderless mx-auto">
                                <thead>
                                    <tr>
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
                                            <td><?= $row['expense'] ?></td>
                                            <td><?= $row['date'] ?></td>
                                            <td>₱<?= $row['amount'] ?></td>
                                        </tr>
                                    <?php } ?>
                                    <h4 class="text-end me-5">Total: <b>₱<?= $output ?></b></h4>
                                    <a href="../edit/generate_pdf.php?date_start=<?= $_POST['date_start']; ?>&date_end=<?= $_POST['date_end']; ?>" class="btn btn-primary">Print</a>
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