<?php
session_start();

require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['firstname']);
    $lastName = trim($_POST['lastname']);
    $username = trim($_POST['username']);
    $staffID = trim($_POST['staffid']); // Use lowercase for consistency
    $userEmail = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirmPassword = $_POST['re-enterpassword'];
    $is_staff = 0; // Default to non-staff

    // Validate email format
    if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email format.';
        header('Location: CreateAccount.php');
        exit();
    }

    // Check if StaffID is valid
    $checkQuery = "SELECT * FROM Staff WHERE staffid = ?";
    $stmt = $connection->prepare($checkQuery);
    $stmt->bind_param("s", $staffID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $is_staff = 1; // Staff exists
    } else {
        $_SESSION['error'] = 'Invalid StaffID.';
        header('Location: Admincreateaccount.php');
        exit();
    }

    // Check for existing email or username
    $checkQuery = "SELECT * FROM USERS WHERE email = ? OR username = ?";
    $stmt = $connection->prepare($checkQuery);
    $stmt->bind_param("ss", $userEmail, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = 'Email or username already exists. Please use a different one.';
        header('Location: Admincreateaccount.php');
        exit();
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $_SESSION['error'] = 'Passwords do not match.';
        header('Location: Admincreateaccount.php');
        exit();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert new user
    $stmt = $connection->prepare("INSERT INTO USERS (firstname, lastname, username, email, password, staffid, is_staff) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $firstName, $lastName, $username, $userEmail, $hashedPassword, $staffID, $is_staff);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Account created successfully.';
        header("Location: AdminDashboard.php");
    } else {
        $_SESSION['error'] = 'Error creating account. Please try again.';
        include ('Location: Admincreateaccount.php');
    }

    $stmt->close();
    $connection->close(); // Close connection
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an Account</title>
    <link rel="icon" type="image/x-icon" href="/img/favicon/sk.ico">
    <link rel="stylesheet" href="css/admincreateaccount.css?v=<?php echo time(); ?>">
</head>

<body>
    <header>
        <a class="logo">Library</a>
    </header>

    <main>
        <div class="detail-form">
            <p>Account Information</p>
            <form action="Admincreateaccount.php" method="POST">
                <div class="init">
                    <label for="firstname">First Name</label>
                    <input type="text" name="firstname" id="firstname" placeholder="Enter First Name" required>
                </div>
                <div class="init">
                    <label for="lastname">Last Name</label>
                    <input type="text" name="lastname" id="lastname" placeholder="Enter Last Name" required>
                </div>
                <div class="init">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" placeholder="Enter Username" required>
                </div>
                <div class="init">
                    <label for="staffid">Staff ID</label>
                    <input type="text" name="staffid" id="staffid" placeholder="Enter Employee Staff ID" required>
                </div>
                <div class="init">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="yours_truly@example.com" required>
                </div>
                <div class="init">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="At least 8 characters" minlength="8" required>
                </div>
                <div class="init">
                    <label for="re-enterpassword">Confirm Password</label>
                    <input type="password" name="re-enterpassword" id="re-enterpassword" placeholder="Re-enter Password" required>
                </div>
                <div class="init1">
                    <input type="submit" value="Sign-Up">
                    <a href="signIn.php">Already have an Account?</a>
                </div>
            </form>
        </div>
    </main>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>

</html>