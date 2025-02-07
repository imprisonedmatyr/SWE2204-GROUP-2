<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="/VL/css/userdashboard.css?v=<?php echo time(); ?>">
    <link rel="icon" href="/VL/img/favicon/sk.ico">
</head>

<body>
    <header>
        <?php
        include 'header_client.php';

        if (!isset($_SESSION['username'])) {
            header('Location: signIn.php');
            exit();
        }
        ?>
    </header>

    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> ! </h1>

    <main class="dashboard">
        <section class="welcome-section">
            <h3>Your library, your world. Manage your activity and account here.</h3>
        </section>

        <section class="dashboard-actions">
            <div class="action-item">
                <a href="Accountinfo.php">
                    <img src="img/fontawesome-free-6.6.0-web/svgs/svgrepo/account-settings-svgrepo-com.svg"
                        alt="Account Settings">
                    <p>Account Settings</p>
                </a>
            </div>

            <div class="action-item">
                <a href="favourites.php">
                    <img src="img/fontawesome-free-6.6.0-web/svgs/svgrepo/database-svgrepo-com.svg" alt="Favorite Books">
                    <p>My Library</p>
                </a>
            </div>
        </section>
    </main>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>

</html>