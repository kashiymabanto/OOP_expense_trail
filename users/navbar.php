<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar Example</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #d1c4e9; /* Light purple */
            border-top: 5px solid #7e57c2; /* Darker purple stripe */
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }
        .navbar .logo {
            font-size: 24px;
            font-weight: bold;
        }
        .navbar ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }
        .navbar ul li {
            margin: 0 15px;
        }
        .navbar ul li a {
            text-decoration: none;
            color: #000;
            font-size: 18px;
        }
        .navbar .search-icon {
            font-size: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">Expense Trail</div>
        <ul>
            <li><a href="petty_cash.php">Petty Cash</a></li>
            <li><a href="accounts.php">Accounts</a></li>
            <li><a href="expense.php">Expense</a></li>
            <li><a href="expense-list.php">Expense List</a></li>
            <li><a href="expense_history.php">Expense History</a></li>
            
        </ul>
        <div class="search-icon"><a href="../signout.php">Logout</a></div>
    </div>
</body>
</html>
