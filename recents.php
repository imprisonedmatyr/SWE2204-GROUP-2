<?php 
session_start();

require 'db_connect.php';

$st = $connection->prepare('select * from books join content on books.book_id=content.book_id order by updated_at desc');
$st->execute();
$result = $st->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recent Activities</title>
    <link rel="stylesheet" href="css/recents.css?v=<?php echo time(); ?>">
    <link rel="icon" href="img/favicon/sk.ico">
</head>

<body>
    <header>
        <?php include 'header_admin.php'; ?>
    </header>

    <main>
        <section class="recents-section">
            <h2>Recent Activities</h2>
            <table class="recents-table">
                <thead>
                    <tr>
                        <th>Book ID</th>
                        <th>Activity</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['book_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Chapter_title']);
                            if (!empty($row['TITLE'])) {
                                echo " \"" . htmlspecialchars($row['TITLE']) . "\"";
                            }
                            if (!empty($row['Chapter_title'])) {
                                echo " - Chapter: \"" . htmlspecialchars($row['Chapter_title']) . "\"";
                            }
                            echo "</td>";
                            echo "<td>" . htmlspecialchars($row['updated_at']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No recent activities found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>


    <footer>
        <p>&copy; 2024 Library Management System. All rights reserved.</p>
    </footer>
</body>

</html>