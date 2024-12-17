<?php
session_start();

// Include required classes
include("../db/connection.php");
include("../db/function.php");
include("../db/user.php");
include("../db/crud.php");

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
}

class PettyCash {
    private $crud;

    public function __construct($crud) {
        $this->crud = $crud;
    }

    public function addCash($userId, $date, $cash) {
        return $this->crud->addCash($userId, $date, $cash);
    }
}

// Instantiate the Database class and get the connection
$db = new Database();
$con = $db->getConnection();

// Instantiate the Auth class and check login status
$auth = new Authcon($con);
$user_data = $auth->checkLogin(); // Calls the checkLogin() method to verify user login status

if ($user_data['user_type'] !== '2') {
    header("Location: ../signout.php");
    exit(); // Stop further script execution
}

// Instantiate the Crud class and PettyCash class
$crud = new user($con);
$pettyCash = new PettyCash($crud);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_cash'])) {
        $id = $user_data['id'];
        $date = $_POST['date'];
        $cash = $_POST['cash'];

        if ($pettyCash->addCash($id, $date, $cash)) {
            $_SESSION['status1'] = "Petty Cash added successfully.";
        } else {
            $_SESSION['status'] = "Failed to add Petty Cash.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="stylesheet" href="../css/admin-petty.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petty Cash</title>
</head>

<body>
   <?php 
   include("../sidebar/admin-navbar.php");
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
                <div class="col-md-6 col-lg-6">
                    <div class="container-fluid d-flex justify-content-center petty_cash mt-2">

                        <div class="box border border-dark rounded-2 w-75 ">
                            <h1 class="text-center mt-3">Petty Cash</h1>
                            <div class="container">
                                <div class="row">
                                <form action="petty_cash.php" method="POST">
                                        <div class="col-md-12">
                                            <input type="hidden" value="<?= $_SESSION['id']; ?>" name="id">
                                            <label for="inputUsername" class="form-label"><b>Date</b></label>
                                            <input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <label for="inputUsername" class="form-label"><b>Petty Cash</b></label>
                                            <input type="text" name="cash" class="form-control" placeholder="Amount">
                                        </div>
                                        <div class="col-md-12 mt-3 d-flex justify-content-evenly">
                                            <button type="submit" name="add_cash" class="btn btn-warning petty_btn">Add</button>
                                        </div>
                                    </form>
                                    </br>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
    </div>






</body>

</html>