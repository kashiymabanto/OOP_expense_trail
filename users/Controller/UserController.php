<?php
class UserController {
    private $userObj;

    public function __construct($userObj) {
        $this->userObj = $userObj;
    }

    // Handle add expense
    public function handleAddExpense($data) {
        if ($this->userObj->payExpense($data['expense'], $data['cash'], $data['date'], $data['id'])) {
            $_SESSION['status1'] = "Transaction Successfully Added";
            header('location: expense.php');
        } else {
            $_SESSION['status'] = "Transaction failed to Add or Insufficient Funds";
            header('location: expense.php');
        }
    }

    // Handle edit expense
    public function handleEditExpense($data) {
        if ($this->userObj->editExpense($data['id'], $data['expense'], $data['cash'])) {
            $_SESSION['status1'] = "Amount Successfully Edited";
            header('location: expense-list.php');
        } else {
            $_SESSION['status'] = "Amount failed to Edit";
            header('location: expense-list.php');
        }
    }

    // Handle delete expense
    public function handleDeleteExpense($data) {
        if ($this->userObj->deleteExpense($data['id'])) {
            $_SESSION['status1'] = "Deleted Successfully";
            header('location: expense-list.php');
        } else {
            $_SESSION['status'] = "Failed to delete";
            header('location: ../expense-list.php');
        }
    }
}
?>
