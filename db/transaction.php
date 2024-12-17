<?php
include_once 'connection.php';

class Transaction {
    private $con;

    public function __construct() {
        $database = new Database();
        $this->con = $database->getConnection();
    }

    public function searchTransactions($startDate, $endDate) {
        $sql = "SELECT * FROM transaction 
                INNER JOIN users ON transaction.users_id = users.id 
                WHERE date BETWEEN ? AND ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getTotalAmount() {
        $sql = "SELECT SUM(amount) AS total FROM transaction";
        $result = $this->con->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}
?>
