<?php
// Start the session
session_start();

require 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: signIn.php');
    exit();
}

try {
    
    $pst = $connection->prepare('SELECT * FROM users WHERE username = ?');
    $pst->bind_param('s', $_SESSION['username']);
    $pst->execute();
    $ps = $pst->get_result();
    $pp = $ps->fetch_assoc();
} catch (Exception $e) {
    echo '<script> alert("".$e->getMessage()); </script>';
}

// setting user details as session variables
$firstName = $pp['firstname'];
$lastName = $pp['lastname'];
$email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'N/A';
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Profile</title>
    <link rel="icon" href="img/favicon/sk.ico">
    <link rel="stylesheet" href="css/accountinfo.css?v=<?php echo time(); ?>">
</head>

<body>
    <header>
        <?php
            include 'header_admin.php';
        ?>
    </header>

    <section>
        <div class="sect0">
            <h2>Account Information</h2>
            <div class="cont">
                <div class="info">
                    <div class="dets">
                        <div><label for="firstname">First Name</label></div>
                        <div><?php echo $firstName; ?></div>
                    </div>
                    <div class="dets">
                        <div><label for="lastname">Last Name</label></div>
                        <div><?php echo $lastName; ?></div>
                    </div>
                    <div class="dets">
                        <div><label for="email">Email</label></div>
                        <div><?php echo $email; ?></div>
                    </div>
                </div>
            </div>
            <div class="settings">
                <a href="accountsettings.php">Update Account Details?</a>
            </div>
        </div>
    </section>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>

</html>