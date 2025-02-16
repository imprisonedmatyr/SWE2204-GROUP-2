<?php
session_start();

require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch and sanitize inputs
    $userEmail = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $userPassword = $_POST['pwd'];

    // Validate email format
    if (filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        // Prepare the SQL statement to find the user
        $title = "SELECT * FROM users WHERE email = ?;";
        $stmt = $connection->prepare($title);

        if ($stmt) {
            $stmt->bind_param("s", $userEmail);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                // Verify the password
                if (password_verify($userPassword, $user['password'])) {
                    // Set session variables
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['firstname'] = $user['first_name'];
                    $_SESSION['lastname'] = $user['last_name'];
                    $_SESSION['is_staff'] = (bool) $user['is_staff'];

                    // Redirect based on user role
                    header('Location: ' . ($_SESSION['is_staff'] ? 'AdminDashboard.php' : 'userdashboard.php'));
                    exit();
                } else {
                    $_SESSION['error'] = 'Incorrect password.';
                    include "signIn.php"; // Redirect back to login
                    $stmt->close();
                    exit();
                }
            } else {
                $_SESSION['error'] = 'Email not found.';
                include "signIn.php"; // Redirect back to login
                $stmt->close();
                exit();
            }
        } else {
            include "signIn.php"; // Redirect back to login
            exit();
        }
    } else {
        include "signIn.php"; // Redirect back to login
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
    <title>Sign-In</title>
    <link rel="icon" type="image/x-icon" href="img/favicon/sk.ico">
    <link rel="stylesheet" href="css/signIn.css?v=<?php echo time(); ?>">
</head>

<body>
    <header class="header">
        <a class="logo">Library</a>
        <div class="user-icon" onclick="toggleDropdown()">
            <div class="user-circle">
                <img src="img\fontawesome-free-6.6.0-web\svgs\regular\account-svgrepo-com.svg" alt="errr loading image">
            </div>
            <div class="dropdown">
                <a href="CreateAccount.php">Standard User</a>
                <a href="Admincreateaccounts.php">Admin</a>
            </div>
        </div>
    </header>

    <section>
        <div class="signin">
            <div class="content">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <h2>Sign-In into your account</h2>
                    <div class="form">
                        <div class="message">
                            <?php
                            if (isset($_SESSION['error'])) {
                                echo '<p style="color: red;">' . htmlspecialchars($_SESSION['error']) . '</p>';
                                unset($_SESSION['error']); // Clear error after displaying
                            }
                            ?>
                        </div>
                        <div class="inputbox">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email" required>
                        </div>
                        <div class="inputbox">
                            <label for="pwd">Password</label>
                            <input type="password" id="pwd" name="pwd" placeholder="Enter your password" required>
                        </div>
                        <div class="inputbox">
                            <input type="submit" value="Log In" id="btn">
                        </div>
                        <div class="links">
                            <a href="CreateAccount.php">Sign Up</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <footer>
        <?php include 'footer.php'; ?>
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