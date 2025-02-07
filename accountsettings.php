<?php
session_start();

// Check if the user is logged in, if not, redirect to the login page
if (!isset($_SESSION['email'])) {
    header('Location: signIn.php');
    exit();
}

// Ensure session variables are set
$firstName = isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'N/A';
$lastName = isset($_SESSION['lastname']) ? htmlspecialchars($_SESSION['lastname']) : 'N/A';
$username = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'N/A';
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <link rel="icon" href="/VL/img/favicon/sk.ico">
    <link rel="stylesheet" href="/VL/css/accountsettings.css?v=<?php echo time(); ?>">
</head>

<body>
    <header>
        <?php
        include 'header_client.php';
        ?>
    </header>

    <main>
        <section class="settings-section">
            <h1>Account Settings</h1>
            <form class="settings-form" action="update_account.php" method="POST">
                <label for="username">Username</label>
                <input type="text" id="username" name="username"
                    value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>

                <label for="name">Full Name</label>
                <input type="text" id="name" name="name"
                    value="<?php echo htmlspecialchars($_SESSION['firstname'] . ' ' . $_SESSION['lastname']); ?>"
                    required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••">

                <button type="submit">Update Information</button>
            </form>
        </section>
    </main>

    <footer>
        <?php include 'footer.php';?>
    </footer>

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
