<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="stylesheet" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EXPENSE</title>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"></a>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <div class="container">
                    <b class="title-bar text-center d-flex justify-content-center">EXPENSE</b>
                </div>
            </div>
        </div>
    </nav>
    <!-- NAVBAR END -->
    <!-- LOGIN PAGE -->

    <div class="login-form container d-flex justify-content-center align-items-center">
        <div class="box border border-dark rounded-2 w-25 mb-5">
            <div class="container mt-4">
                <div class="row">
                <?php
    if (isset($_SESSION['status'])) {
    ?>
        <div class="text-center">
            <div class="alert alert-danger alert-dismissible fade show mx-auto" role="alert" style="width: 300px; border-radius: 20px;">
                <strong> Hey! </strong> <?php echo $_SESSION['status']; ?>
            
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
                
            </div>
        </div>
    <?php

        unset($_SESSION['status1']);
    }
    ?>
                    <div class="cold-md-12 text-center">
                        <h3 class="">EXPENSE LOGIN</h3>
                    </div>
                    <form action="db/login.php" method="POST">
                        <div class="col-md-12">
                            <label for="inputUsername" class="form-label"><b>Username</b></label>
                            <input type="text" name="user" class="form-control" placeholder="Username">
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="inputPassword" class="form-label"><b>Password</b></label>
                            <input type="password" name="pass" class="form-control" placeholder="Password">
                        </div>
                        <div class="col-md-12 d-flex justify-content-center">
                            <button type="submit" name="login" class="btn btn-primary w-75 mt-4">LOGIN</button>
                        </div>
                    </form>
                    <div class="col-md-12 mt-3">
                        <p class="create_acc">Don't have an account? <a href="signup.php">Create Here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>





    
</body>

</html>