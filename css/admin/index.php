<?php
session_start();

// Include necessary files
include("../db/connection.php");
include("../db/function.php");
include("../db/user.php");

// Auth class to handle user login and permission checks
class Authcon {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Method to check if the user is logged in
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

    // Method to verify user type (only admin allowed in this case)
    public function hasAdminPermission($userType) {
        return $userType === '1'; // Check if user is admin (user_type '1')
    }
}

// UserHandler class to handle user-related operations
class UserHandler {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Method to fetch all users of type '1' (admin)
    public function getAdmins() {
        $sql = "SELECT * FROM users WHERE user_type = '1'";
        return $this->db->query($sql); // Execute and return the result
    }
}

// Instantiate the Database class and get the connection
$db = new Database();
$con = $db->getConnection();

// Instantiate the Auth class and check login status
$auth = new Auth($con);
$user_data = $auth->checkLogin(); // Calls the checkLogin() method of the Auth class to verify user login status

// Verify the user's type to ensure they have the appropriate permissions
if ($user_data['user_type'] !== '2') {
    header("Location: ../signout.php");
    exit(); // Stop further script execution
}

// Instantiate UserHandler class to fetch admin users
$userHandler = new UserHandler($con);
$users = $userHandler->getAdmins(); // Get all admin users

// Now, you can loop through $admins to display the data or process it further
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="../css/index-admin.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Users</title>
</head>

<body>
<?php
include("../sidebar/admin-navbar.php")
?>
            <main>
                <div class="container-fluid mt-4">
                    <div class="container d-flex justify-content-between">
                        <h2 class="pt-3">List of Users</h2>
                    </div>
                    <div class="container table-borrwed mt-5">
                        <table class="table table-striped mb-5 w-75 mx-auto table-bordered">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $row) { ?>
                                <tr>
                                    <td><?= $row['id']; ?></td>
                                    <td><?= $row['name']; ?></td>
                                    <td><?= $row['username']; ?></td>
                                    <td><?= $row['password']; ?></td>
                                    <td>
                                        <a href="../edit/edit_user.php?id=<?= $row['id']; ?>" class="btn btn-primary">Edit</a>
                                        <a href="../edit/delete_user.php?id=<?= $row['id']; ?>" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
    
    

</body>

</html>
