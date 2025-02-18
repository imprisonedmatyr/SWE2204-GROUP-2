<?php
session_start();

require 'E-Library/db_connect.php';

// Check if it's an AJAX request for loading more books
if (isset($_GET['section']) && isset($_GET['limit']) && isset($_GET['offset'])) {
    $section = $_GET['section'];
    $limit = intval($_GET['limit']);
    $offset = intval($_GET['offset']);

    if ($section === 'featured') {
        $stmt = $connection->prepare("SELECT book_id, TITLE, AUTHOR, `BOOK_COVER` AS IMAGE FROM books ORDER BY book_id ASC LIMIT ? OFFSET ?");
    } elseif ($section === 'most-visited') {
        $stmt = $connection->prepare("SELECT book_id, TITLE, AUTHOR, `BOOK_COVER` AS IMAGE FROM books WHERE visits >= 20 ORDER BY visits DESC LIMIT ? OFFSET ?");
    } else {
        echo json_encode([]);
        exit;
    }

    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch books and return them as JSON
    $books = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($books);
    exit;
}

// Check if a specific book's details page is requested (for visit tracking)
if (isset($_GET['book_id'])) {
    $book_id = intval($_GET['book_id']);

    // Increment the visits count for the book
    $title = "UPDATE BOOKS SET visits = visits + 1 WHERE book_id = $book_id";
    $connection->query($title);

    // Fetch the book details
    $title = "SELECT * FROM BOOKS WHERE book_id = $book_id";
    $result = $connection->query($title);

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        // Display book details
        echo "<h1>" . htmlspecialchars($book['TITLE']) . "</h1>";
        echo "<p>by " . htmlspecialchars($book['AUTHOR']) . "</p>";
        echo "<img src='db/Book_Covers/" . htmlspecialchars($book['BOOK COVER']) . "' alt='" . htmlspecialchars($book['TITLE']) . " book cover'>";
        echo "<p>Visits: " . $book['visits'] . "</p>";
        exit;
    } else {
        echo "Book not found.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library</title>
    <link rel="icon" type="image/x-icon" href="E-Library/img/favicon/sk.ico">
    <link rel="stylesheet" href="E-Library/css/styles1.css?v=<?php echo time(); ?>">
	<link rel="stylesheet" href="E-Library/css/header.css?v=<?php time(); ?>">
	<link rel="stylesheet" href="E-Library/css/footer.css?v=<?php echo time(); ?>">
</head>

<body>
	<?php
	include 'E-Library/header.php';
	?>

    <!-- Search Bar -->
    <div class="search-bar">
        <div>
            <form id="form">
                <div class="sb">
                    <input type="text" name="title" id="searchInput" placeholder="Search for books, authors, or genres...">
                    <input type="text" name="author" placeholder="Author">
                    <select name="genre" class="select-option">
                        <option value="">Category</option>
                        <?php
                        $cat = $connection->prepare("SELECT DISTINCT CATEGORY FROM books");
                        $cat->execute();
                        $dog = $cat->get_result();
                        ?>
                        <?php while ($rat = $dog->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($rat['CATEGORY']); ?>">
                                <?php echo htmlspecialchars($rat['CATEGORY']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div style="padding-top: 20px; justify-content: center;" class="sb"><input type="submit" value="Search" class="submit-btn"></div>
            </form>
        </div>
    </div>
    <h3 id="h3">Search Results</h3>
    <div class="search-results" id="rs">
        <div class="book-list" id="results"></div>
    </div>

    <!-- Featured Books Section -->
    <div class="featured-section" id="fb">
        <h2>Featured Books</h2>
        <div class="book-list" id="featured-books">
            <?php
            $title = 'SELECT book_id, TITLE, AUTHOR, BOOK_COVER AS IMAGE FROM BOOKS ORDER BY book_id ASC LIMIT 10';
            $result = $connection->query($title);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="book-item">';
                    echo '<div class="book-coverpage">';
                    echo '<a href="E-Library/bookinfo.php?book_id=' . $row['book_id'] . '"><img src="E-Library/img/Book_Covers/' . htmlspecialchars($row['IMAGE']) . '" alt="' . htmlspecialchars($row['TITLE']) . ' book cover image"></a>';
                    echo '</div>';
                    echo '<div class="book-title">';
                    echo '<a class="title-anchor" href="E-Library/bookinfo.php?book_id=' . $row['book_id'] . '">' . htmlspecialchars($row['TITLE']) . '</a>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No books found</p>';
            }
            ?>
        </div>
        <button class="show-more-btn" onclick="loadMoreBooks('featured')">Show More</button>
    </div>

    <!-- Most Visited Books Section -->
    <div class="most-visited-section" id="msv">
        <h2>Most Visited</h2>
        <div class="book-list" id="most-visited-books">
            <?php
            $title = 'SELECT book_id, TITLE, AUTHOR, `BOOK_COVER` AS IMAGE FROM BOOKS WHERE visits > 0 ORDER BY visits DESC LIMIT 10';
            $result = $connection->query($title);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="book-item">';
                    echo '<div class="book-coverpage">';
                    echo '<a href="E-Library/bookinfo.php?book_id=' . $row['book_id'] . '"><img src="E-Library/img/Book_Covers/' . htmlspecialchars($row['IMAGE']) . '" alt="' . htmlspecialchars($row['TITLE']) . ' book cover image"></a>';
                    echo '</div>';
                    echo '<div class="book-title">';
                    echo '<a class="title-anchor" href="E-Library/bookinfo.php?book_id=' . $row['book_id'] . '">' . htmlspecialchars($row['TITLE']) . '</a>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No books found</p>';
            }
            ?>
        </div>
        <button class="show-more-btn" onclick="loadMoreBooks('most-visited')">Show More</button>
    </div>

    <!-- Footer -->
    <?php include 'E-Library/footer.php'; ?>

    <script src="E-Library/Script_Functions.js"></script>

    <script>
        document.getElementById('form').addEventListener('submit', async function(event) {
            event.preventDefault();

            const formData = new FormData(event.target);

            const response = await fetch('E-Library/searchResult.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(formData)

            });
            document.getElementById('results').innerHTML = '';

            const data = await response.json();
            console.log(data);

            // Hide the other sections and display only the results section
            document.getElementById('fb').style.display = 'none';
            document.getElementById('msv').style.display = 'none';
            document.getElementById('rs').style.display = 'block';
            document.getElementById('h3').style.display = 'block';

            if (data.message) {
                document.getElementById('results').innerHTML = data.message
            } else {
                data.forEach(book => {
                    const bookDiv = document.createElement('div');
                    bookDiv.classList.add('book-item');

                    const coverPage = document.createElement('div');
                    coverPage.classList.add('book-coverpage');
                    const coverImage = document.createElement('img');
                    coverImage.src = 'E-Library/img/Book_Covers/' + book.IMAGE;
                    coverImage.alt = book.TITLE + 'book cover image';
                    coverPage.appendChild(coverImage);
                    bookDiv.appendChild(coverPage);

                    const titleDiv = document.createElement('div');
                    titleDiv.classList.add('book-title');
                    const titleLink = document.createElement('a');
                    titleLink.href = 'E-Library/bookinfo.php?book_id=' + book.book_id;
                    titleLink.textContent = book.TITLE;
                    titleDiv.appendChild(titleLink);
                    bookDiv.appendChild(titleDiv);

                    document.getElementById('results').appendChild(bookDiv);
                });
            }
        });
        window.onload = function() {
            document.getElementById('rs').style.display = 'none';
            document.getElementById('h3').style.display = 'none';
        };
    </script>
</body>

</html>