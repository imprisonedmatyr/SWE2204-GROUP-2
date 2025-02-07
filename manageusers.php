<?php
session_start();
require 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="/VL/css/manageusers.css?v=<?php echo time(); ?>">
    <link rel="icon" href="/VL/img/favicon/sk.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body>
    <header>
        <?php include "header_admin.php"; ?>
    </header>

    <main>
        <section class="users-section">
            <h2>Manage Users</h2>
            <table class="users-table">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <!-- <th>Actions</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch users from the database
                    $bool = 0;
                    $stmt = $connection->prepare('SELECT * FROM USERS where is_staff = ?'); // Assuming there's a 'users' table
                    $stmt->bind_param("i", $bool);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    ?>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['firstname']); ?></td>
                                <td><?php echo htmlspecialchars($row['lastname']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']) ?></td>
                                <td style="font-color: Green;"><?php echo $row['is_banned'] ? 'Banned' : 'Active'; ?></td>
                                <!-- <td>
                                    <button class="ban-btn" onclick="banUser(<?php echo $row['username']; ?>)">Ban</button>
                                </td> -->
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <?php include 'footer.php';?>
    </footer>

    <script>
        function banUser(userId) {
            if (confirm("Are you sure you want to ban this user?")) {
                fetch('ban_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ user_id: userId })
                })
                    .then(response => response.json())
                    .then(data => {
                        toastr.success(data.message); // Display success notification
                        location.reload(); // Reload the page
                    })
                    .catch((error) => {
                        toastr.error('An error occurred. Please try again.'); // Display error notification
                        console.error('Error:', error);
                    });
            }
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
</body>

</html>