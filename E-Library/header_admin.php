<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>">
    <link rel="icon" href="img/favicon/sk.ico">
    <title>Admin Dashboard</title>
</head>

<body>
    <header class="header">
        <a class="logo">Admin</a>
        <div class="user-icon" onclick="toggleDropdown()">
            <div class="user-circle">
                <?php
                // Display the first letter of the username if user session exits
                if (isset($_SESSION['username'])) {
                    echo strtoupper(substr($_SESSION['username'], 0, 1));
                }
                ?>
            </div>
            <div class="dropdown">
                <a href="AdminDashboard.php">DashBoard</a>
                <a href="manageusers.php">Users</a>
                <a href="managecatalog.php">Books</a>
                <a href="Accountinfo_admin.php">My Account</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </header>

    <script src="Script_Functions.js"></script>
</body>

</html>