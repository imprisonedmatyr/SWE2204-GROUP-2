<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/VL/css/header.css?v=<?php time(); ?>">
    <link rel="icon" href="/VL/img/favicon/sk.ico">
    <title>Library</title>
    <style>
        /* Basic header styles */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #333;
            color: white;
        }

        .header .logo {
            text-decoration: none;
            color: #ff6f61;
            font-size: 24px;
            font-weight: bold;
        }

        .user-icon {
            position: relative;
            cursor: pointer;
        }

        .user-circle {
            width: 35px;
            height: 35px;
            background-color: #444;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #ff6f61;
            font-weight: bold;
        }

        .dropdown {
            display: none;
            position: absolute;
            right: 0;
            background: white;
            color: black;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            z-index: 1000;
        }

        .dropdown a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: black;
        }

        .dropdown a:hover {
            background: #f0f0f0;
        }

        .user-icon.active .dropdown {
            display: block;
        }
    </style>
</head>

<body>
    <header class="header">
        <a href="home1.php" class="logo">Library</a>
        <div class="user-icon" onclick="toggleDropdown()">
            <div class="user-circle">
                <?php
                // Display the first letter of the username if set
                if (isset($_SESSION['is_staff']) && $_SESSION['is_staff'] != 1) {
                    echo strtoupper(substr($_SESSION['username'], 0, 1));
                } else {
                    echo '<img src="/VL/img/fontawesome-free-6.6.0-web/svgs/regular/account-svgrepo-com.svg" style="width: 2.92rem; height: auto;">';
                }
                ?>
            </div>
            <div class="dropdown">
                <?php if (isset($_SESSION['is_staff']) && $_SESSION['is_staff'] != 1): ?>
                    <a href="home1.php" class="logo">Home</a>
                    <a href="search.php">Browse</a>
                    <a href="userdashboard.php">Dashboard</a>
                    <a href="Accountinfo.php">Profile</a>
                    <a href="favourites.php">My Library</a>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="home1.php" class="logo">Home</a>
                    <a href="signIn.php">log In</a>
                    <a href="CreateAccount.php">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <script>
        function toggleDropdown() {
            document.querySelector('.user-icon').classList.toggle('active');
        }

        // Close the dropdown if clicked outside
        window.addEventListener('click', function(e) {
            if (!document.querySelector('.user-icon').contains(e.target)) {
                document.querySelector('.user-icon').classList.remove('active');
            }
        });
    </script>
</body>

</html>