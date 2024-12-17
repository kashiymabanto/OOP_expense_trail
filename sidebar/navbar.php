<!DOCTYPE html>
<html>
<head>
    <title>Navbar</title>
    <style>
         *{
            padding: 0;
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
         }

        nav {
            background-color: #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        nav ul li {
            margin: 0 10px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
        }

        nav ul li a:hover {
            background-color: #444;
        }

        nav a.logout-button {
            margin-left: auto;
            color: white;
            text-decoration: none!important; /* Move the logout button to the far right */
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="#">EXPENSE TRAIL</a></li>
            <li><a href="petty_cash.php">Petty Cash</a></li>
            <li><a href="expense.php">Expense</a></li>
            <li><a href="expense-list.php">Expense List</a></li>
            <!-- <li><a href="history.php">History</a></li> -->
        </ul>
        <a class="logout-button" href="../signout.php">LOGOUT</a>
    </nav>
</body>
</html>