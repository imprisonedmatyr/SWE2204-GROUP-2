<?php
session_start();

require 'db_connect.php';

// Redirect if the user is not logged in
if (!isset($_SESSION['username'])) {
    header('Location: signIn.php');
    exit();
}

// Set the username after confirming the session
$username = $_SESSION['username'];

// Fetch user's favorite books from the database
$titleQuery = "SELECT favorite_books.book_id, TITLE, AUTHOR, BOOK_COVER FROM favorite_books 
               JOIN books ON books.book_id = favorite_books.book_id WHERE favorite_books.username = ?";
$stmt = $connection->prepare($titleQuery);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$favorites = []; // Array to store favorite books
while ($row = $result->fetch_assoc()) {
    $favorites[] = $row; // Add each book to the array
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorite Books</title>
    <link rel="stylesheet" href="/VL/css/favourites.css?v=<?php echo time(); ?>">
    <link rel="icon" href="/VL/img/favicon/sk.ico">
</head>

<body>
    <header>
        <?php include 'header_client.php'; ?>
    </header>

    <main>
        <h1>Keep track of all books you don't want to miss out on!</h1>
        <div class="favorites-section">
            <?php if (!empty($favorites)): ?>
                <?php foreach ($favorites as $book): ?>
                    <?php
                    $book_id = $book['book_id'];
                    $image = $book['BOOK_COVER'];

                    $count = $connection->prepare("SELECT count(Chapter_title) as TOTAL_CHAPTERS from content WHERE book_id = ?");
                    $count->bind_param('i', $book_id);
                    $count->execute();
                    $count1 = $count->get_result();
                    $count2 = $count1->fetch_assoc();
                    ?>
                    <div class="book-item">
                        <a href="bookinfo.php?book_id=<?php echo htmlspecialchars($book_id) ?>">
                            <img src="img/Book_Covers/<?php echo htmlspecialchars($image); ?>" alt="Book Cover">
                        </a>
                        <div class="title"><a href="bookinfo.php?book_id=<?php echo htmlspecialchars($book_id) ?>"><?php echo htmlspecialchars($book['TITLE']); ?></a></div>
                        <div class="author"><strong>Author:</strong> <?php echo htmlspecialchars($book['AUTHOR']); ?></div>
                        <div class="chapters"><strong>Total Chapters:</strong> <a href="bookinfo.php?book_id=<?php echo htmlspecialchars($book_id) ?>"><?php echo htmlspecialchars($count2['TOTAL_CHAPTERS']); ?><?php echo $count2['TOTAL_CHAPTERS'] == 1 ? ' CHAPTER' : ' CHAPTERS'; ?></a></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No particular books ventured.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>

</html>