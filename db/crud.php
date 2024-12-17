  0   <?php

    include_once("connection.php"); // Include the database connection file

    // Instantiate the Database class and get the connection
    $db = new Database();  
    $con = $db->getConnection();  // Get the database connection

    // Check if the connection is successful
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    class User {
        private $con;   

        public function __construct($connection) {
            $this->con = $connection;
        }

        // Signup method
        public function signup($name, $username, $password) {
            $sql = "INSERT INTO users (name, username, password, user_type) VALUES ('$name', '$username', '$password', '1')";
            return mysqli_query($this->con, $sql);
        }

        // Add cash method
        public function addCash($id, $date, $cash) {
            $sql1 = "SELECT * FROM history_cash WHERE users_id = '$id'";
            $result = mysqli_query($this->con, $sql1);
            $row = mysqli_fetch_assoc($result);

            $sql2 = "SELECT * FROM petty_cash";
            $result2 = mysqli_query($this->con, $sql2);
            $row2 = mysqli_fetch_assoc($result2);

            if ($row['date'] != $date) {
                $newCash = $row2['cash'] + $cash;
                $sql = "INSERT INTO history_cash(cash, date, users_id) VALUES ('$cash', '$date', '$id')";
                $sql3 = "UPDATE petty_cash SET cash = '$newCash'";
                mysqli_query($this->con, $sql3);
                return mysqli_query($this->con, $sql);
            } else {
                $newCash = $row2['cash'] + $cash;
                $sql2 = "UPDATE petty_cash SET cash = '$newCash'";
                $sql3 = "INSERT INTO history_cash (cash, date, users_id) VALUES ('$cash', '$date', '$id')";
                mysqli_query($this->con, $sql3);
                return mysqli_query($this->con, $sql2);
            }
        }

        // Add account method
        public function addAccount($name) {
            $sql = "INSERT INTO expense_account (name) VALUES ('$name')";
            return mysqli_query($this->con, $sql);
        }

        // Pay expense method
        public function payExpense($expense, $cash, $date, $id) {
            // Check if there is enough balance in petty_cash
            $sql1 = "SELECT * FROM petty_cash";
            $result1 = mysqli_query($this->con, $sql1);
            $row1 = mysqli_fetch_assoc($result1);

            if ($row1['cash'] >= $cash) {
                // Update petty_cash balance
                $newCash = $row1['cash'] - $cash;
                $sql2 = "UPDATE petty_cash SET cash = '$newCash'";
                mysqli_query($this->con, $sql2);

                // Insert transaction into the transaction table
                $sql = "INSERT INTO transaction (expense, amount, date, users_id) VALUES ('$expense', '$cash', '$date', '$id')";
                return mysqli_query($this->con, $sql);
            }
            return false; // Return false if insufficient funds
        }



        // Edit expense method
        public function editExpense($id, $expense, $amount) {
            $sql1 = "SELECT * FROM transaction WHERE id = '$id'";
            $result1 = mysqli_query($this->con, $sql1);
            $row1 = mysqli_fetch_assoc($result1);

            $sql2 = "SELECT * FROM petty_cash";
            $result2 = mysqli_query($this->con, $sql2);
            $row2 = mysqli_fetch_assoc($result2);

            if ($row2['cash'] >= $amount) {
                if ($row1['amount'] >= $amount) {
                    $newCash = $row1['amount'] - $amount;
                    $newCash1 = $newCash + $row2['cash'];
                    $sql2 = "UPDATE petty_cash SET cash = '$newCash1'";
                    mysqli_query($this->con, $sql2);

                    $sql3 = "UPDATE transaction SET amount = '$amount' WHERE id = '$id'";
                    return mysqli_query($this->con, $sql3);
                } else {
                    $newCash2 = $amount - $row1['amount'];
                    $newCash3 = $row2['cash'] - $newCash2;
                    $sql2 = "UPDATE petty_cash SET cash = '$newCash3'";
                    mysqli_query($this->con, $sql2);

                    $sql3 = "UPDATE transaction SET amount = $amount WHERE id = '$id'";
                    return mysqli_query($this->con, $sql3);
                }
            }
            return false;
        }

        // Delete expense method
        public function deleteExpense($id) {
            $sql1 = "SELECT * FROM transaction WHERE id = '$id'";
            $result1 = mysqli_query($this->con, $sql1);
            $row1 = mysqli_fetch_assoc($result1);

            $sql2 = "SELECT * FROM petty_cash";
            $result2 = mysqli_query($this->con, $sql2);
            $row2 = mysqli_fetch_assoc($result2);

            $newamount = $row2['cash'] + $row1['amount'];

            var_dump($newamount);
            $sql = "DELETE FROM transaction WHERE id = $id";
            if (mysqli_query($this->con, $sql)) {
                $sql3 = "UPDATE petty_cash SET cash = '$newamount'";
                return mysqli_query($this->con, $sql3);
            }
            return false;
        }

        // Edit user method
        public function editUser($id, $name, $user, $pass) {
            $sql = "UPDATE users SET name = '$name', username = '$user', password = '$pass' WHERE id = '$id'";
            return mysqli_query($this->con, $sql);
        }

        // Delete user method
        public function deleteUser($id) {
            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }
        
        //getUserID
        public function getUserById($userId)
        {
            $query = "SELECT * FROM users WHERE id = ?";
            $stmt = $this->con->prepare($query);
    
            // Bind parameters
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                // Fetch and return the user
                return $result->fetch_assoc();
            }
    
            return null; // Return null if no result found
        }
    


        // Method to get a single expense by ID




        public function getExpenseById($expenseId, $userId) {
            $query = "SELECT * FROM  transaction   WHERE id = ? AND users_id = ? LIMIT 1";
            $stmt = $this->con->prepare($query);

            // Bind parameters
            $stmt->bind_param("ii", $expenseId, $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Fetch and return the expense
                return $result->fetch_assoc();
            }

            return null;  // Return null if no result found
        }

    }







    ?>
