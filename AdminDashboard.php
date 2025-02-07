<?php
session_start();

require 'db_connect.php';

if (!isset($_SESSION["is_staff"]) || $_SESSION["is_staff"] != 1) {
    header("Location: userdashboard.php");
    exit();
}

// Include your database connection


// Query to get popular books based on visits
$trendingQuery = "SELECT * FROM BOOKS WHERE visits > 20 order by visits desc LIMIT 5 ";
$trendingResult = $connection->query($trendingQuery);
if (!$trendingResult) {
    die("Query failed: " . $connection->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DashBoard</title>
    <link rel="icon" href="/VL/img/favicon/sk.ico">
    <link rel="stylesheet" href="/VL/css/AdminDashboard.css?v=<?php echo time(); ?>">
</head>

<body>
    <header>
        <?php
        include 'header_admin.php';
        ?>
    </header>

    <main class="sect0div">
        <section class="sect0">
            <div class="recents">
                <a href="recents.php"><img src="img/fontawesome-free-6.6.0-web/svgs/svgrepo/history-svgrepo-com.svg" alt="history icon"></a>
                <a href="recents.php">
                    <p>Recent Activity</p>
                </a>
            </div>

            <div class="manageus">
                <a href="manageusers.php"> <img src="img/fontawesome-free-6.6.0-web/svgs/svgrepo/users-people-svgrepo-com.svg" alt="Manage users icon"> </a>
                <a href="manageusers.php"><p>Manage Users</p></a>
            </div>

            <div class="managecat">
                <a href="managecatalog.php"><img src="img/fontawesome-free-6.6.0-web/svgs/svgrepo/database-svgrepo-com.svg" alt="open book image"></a>
                <a href="managecatalog.php"><p>Manage catalog</p></a>
            </div>
        </section>

        <div class="trend">
            <img src="img/fontawesome-free-6.6.0-web/svgs/svgrepo/trending-up-svgrepo-com.svg" alt="trending symbol">
            <p>Porpular Reads</p>
        </div>

        <section class="sect2">
            <div class="popularreads">
                <?php if ($trendingResult && $trendingResult->num_rows > 0): ?>
                    <?php while ($book = $trendingResult->fetch_assoc()): ?>
                        <div class="book-item">
                            <img src="img/Book_Covers/<?php echo htmlspecialchars($book['BOOK_COVER']); ?>"
                                alt="<?php echo htmlspecialchars($book['TITLE']); ?> book cover">
                            <div class="title"><?php echo htmlspecialchars($book['TITLE']); ?></div>
                            <div><strong>Author:</strong><?php echo htmlspecialchars($book['AUTHOR']); ?></div>
                            <div class="category"><strong>Category:</strong> <?php echo htmlspecialchars($book['CATEGORY']); ?></div>
                            <div class="status"><strong>Status:</strong> <?php echo htmlspecialchars($book['STATUS']); ?></div>
                            <div class="visits"><strong><?php echo htmlspecialchars($book['visits']); ?></strong> Visits</div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No trending books found.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>

</body>

</html>
