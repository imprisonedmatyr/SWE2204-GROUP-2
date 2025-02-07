<?php
session_start();

require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch and sanitize inputs
    $firstName = trim($_POST['firstname']);
    $lastName = trim($_POST['lastname']);
    $username = trim($_POST['username']);
    $userEmail = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirmPassword = $_POST['re-enterpassword'];
    $staffid = NULL; // NULL for non-staff users
    $is_staff = 0;   // Default to non-staff

    // Validate email
    if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email format.';
        header('Location: CreateAccount.php');
        exit();
    }

    // Check for existing email and username
    $checkQuery = "SELECT * FROM USERS WHERE email = ? OR username = ?";
    $stmt = $connection->prepare($checkQuery);
    $stmt->bind_param("ss", $userEmail, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = 'Email or username already exists. Please use a different one.';
        header('Location: CreateAccount.php');
        exit();
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $_SESSION['error'] = 'Passwords do not match.';
        header('Location: CreateAccount.php');
        exit();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert new user
    $stmt = $connection->prepare("INSERT INTO USERS (firstname, lastname, username, email, password, staffid, is_staff) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $firstName, $lastName, $username, $userEmail, $hashedPassword, $staffid, $is_staff);
    
    if ($stmt->execute()) {
        $_SESSION['is_staff'] = false;

        header("Location: signIn.php");
        $stmt->close();
        exit();
    } else {
        header('Location: CreateAccount.php');
        $stmt->close();
        exit();
    }
}

$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an Account</title>
    <link rel="icon" type="image/x-icon" href="/VL/img/favicon/sk.ico">
    <link rel="stylesheet" href="/VL/css/createaccount.css?v=<?php echo time();?>">
</head>
<body>
    <header>
        <a class="logo">Library</a>
    </header>

    <section class="sect0">
        <div class="detail-form">
            <h3>Account Information</h3>
            <?php
            if (isset($_SESSION['error'])) {
                echo "<div class='error-message'>" . $_SESSION['error'] . "</div>";
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo "<div class='success-message'>" . $_SESSION['success'] . "</div>";
                unset($_SESSION['success']);
            }
            ?>
            <form action="CreateAccount.php" method="POST">
                <div class="input">
                    <label for="firstname">First Name</label>
                    <input type="text" name="firstname" id="firstname" placeholder="Enter First Name" required>
                </div>
                <div class="input">
                    <label for="lastname">Last Name</label>
                    <input type="text" name="lastname" id="lastname" placeholder="Enter Last Name" required>
                </div>
                <div class="input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" placeholder="Enter Username" required>
                </div>
                <div class="input">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="yours_truly@example.com" required>
                </div>
                <div class="input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter Password" required>
                </div>
                <div class="input">
                    <label for="re-enterpassword">Confirm Password</label>
                    <input type="password" name="re-enterpassword" id="re-enterpassword" placeholder="Confirm Password" required>
                </div>
                <div class="input">
                    <input type="submit" value="Sign-Up" id="signInbtn">
                </div>
            </form>
            <div class="links">
                <a href="signIn.php">Already have an Account?</a>
            </div>
        </div>
    </section>

    <footer>
        <?php include 'footer.php';?>
    </footer>
</body>
</html>