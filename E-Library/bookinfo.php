<?php
session_start();

require 'db_connect.php';

$book = null;

// Check if a specific book's details page is requested
if (isset($_GET['book_id'])) {
    $book_id = intval($_GET['book_id']);
    $_SESSION['book_id'] = $book_id;

    // Increment the visits count for the book
    $updateQuery = "UPDATE BOOKS SET visits = visits + 1 WHERE book_id = ?";
    $stmt = $connection->prepare($updateQuery);
    $stmt->bind_param('i', $book_id);
    $stmt->execute();

    // Fetch the book details
    $title = "SELECT * FROM BOOKS WHERE book_id = ?";
    $stmt = $connection->prepare($title);
    $stmt->bind_param('i', $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
    } else {
        echo "Book not found.";
        exit;
    }

    // Fetch chapter details
    $chapterQuery = "SELECT * FROM content WHERE book_id = ?";
    $stmt = $connection->prepare($chapterQuery);
    $stmt->bind_param('i', $book_id);
    $stmt->execute();
    $chapterResult = $stmt->get_result();
} else {
    echo "No book selected.";
    exit;
}

// Handle review submission
// if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['review'])) {
//     $review_content = trim($_POST['review']);
//     if (!empty($review_content) && isset($_SESSION['username'])) {
//         $title = 'INSERT INTO REVIEWS (book_id, username, review) VALUES (?, ?, ?)';
//         $stmt = $connection->prepare($title);
//         $stmt->bind_param('iss', $book_id, $_SESSION['username'], $review_content);
//         $stmt->execute();
//     }
// }

// Handle bookmarking
if (isset($_POST['bookmark'])) {
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        // Check if the book is already bookmarked
        $checkQuery = "SELECT * FROM favorite_books WHERE book_id = ? AND username = ?";
        $stmt = $connection->prepare($checkQuery);
        $stmt->bind_param('is', $book_id, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // Not bookmarked yet, insert
            $title = 'INSERT INTO favorite_books (book_id, username) VALUES (?, ?)';
            $stmt = $connection->prepare($title);
            $stmt->bind_param('is', $book_id, $username);
            $stmt->execute();
            echo json_encode(['success' => true]);
        } else {
            // Already bookmarked, you might want to implement an "unbookmark" feature here
            echo json_encode(['success' => false, 'message' => 'Book is already bookmarked']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['TITLE']); ?></title>
    <link rel="stylesheet" href="css/bookinfo.css?v=<?php echo time(); ?>">
    <link rel="icon" type="image/x-icon" href="img/favicon/sk.ico">
</head>

<body>
    <header>
        <?php
        include 'header_client.php';
        ?>
    </header>

    <main>
        <section class="book-info-section">
            <div class="book-details">
                <div class="book-cover">
                    <img src="img/Book_Covers/<?php echo htmlspecialchars($book['BOOK_COVER']); ?>"
                        alt="<?php echo htmlspecialchars($book['TITLE']); ?> cover" id="book-cover">
                </div>
                <div class="book-meta">
                    <h1 id="book-title"><?php echo htmlspecialchars($book['TITLE']); ?></h1>
                    <p><strong>Author:</strong> <span
                            id="book-author"><?php echo htmlspecialchars($book['AUTHOR']); ?></span></p>
                    <p><strong>Year Published:</strong> <span
                            id="book-published"><?php echo htmlspecialchars($book['PUBLICATION YEAR']); ?></span></p>
                    <p><strong>Category:</strong> <span
                            id="book-pages"><?php echo htmlspecialchars($book['CATEGORY']); ?></span></p>
                    <p><strong>Genre:</strong> <span
                            id="book-genre"><?php echo htmlspecialchars($book['GENRE']); ?></span></p>
                    <p><strong>Availability:</strong> <span
                            id="book-availability"><?php echo htmlspecialchars($book['STATUS']); ?></span></p>
                    <p><strong>ISBN:</strong> <span id="book-isbn"><?php echo htmlspecialchars($book['book_id']); ?></span>
                    </p>
                </div>
            </div>
            <div class="bookmark-icon">
                <button id="bookmark-button" onclick="toggleBookmark()">
                    <img src="img/fontawesome-free-6.6.0-web/svgs/bookmark/clear-bookmark.svg" alt="Un-Bookmarked-image" width="20px">
                    <strong>Bookmark</strong>
                </button>
            </div>

            <!-- Book Description Section -->
            <div class="book-description">
                <h2>Description</h2>
                <?php
                $file_path = '';

                if ($book && !empty($book['short_description'])) {
                    $file_path = $_SERVER['DOCUMENT_ROOT'] . "Summary/" . $book['short_description'];
                }

                ?>
                <?php if (!empty($file_path) && $file_path != null && file_exists($file_path)): ?>
                    <?php
                    // Fetch and display the summary content
                    $desc = file_get_contents($file_path);
                    $desc = nl2br(htmlspecialchars($desc)); // Convert line breaks and escape HTML
                    ?>
                    <div class="summary-content">
                        <?php echo $desc; ?>
                    </div>
                <?php else: ?>
                    <p>No description available for this book.</p>
                <?php endif; ?>
            </div>

            <!-- Chapter Selection Section -->
            <div class="chapter-selection">
                <h2>Select Chapter to Read</h2>
                <?php if ($chapterResult->num_rows > 0): ?>
                    <ul class="chapter-list">
                        <?php while ($chapter = $chapterResult->fetch_assoc()): ?>
                            <li>
                                <a
                                    href="read_chapter.php?chapter_id=<?php echo $chapter['ChapterID']; ?>&book_id=<?php echo $book_id; ?>">
                                    <?php echo htmlspecialchars($chapter['Chapter_title']); ?>
                                </a>
                                <!-- Download icon or button -->
                                <?php
                                // Assuming the file path is stored in the database or known
                                $file_path = "chapters/" . $chapter['File_path'];
                                if (file_exists($file_path)): ?>
                                    <a href="<?php echo $file_path;?>" class="download-icon" download="<?php echo $chapter['File_path'];?>">
                                        <img src="img\fontawesome-free-6.6.0-web\svgs\solid\download.svg" alt="Download" style="width: 20px; height: 20px;">
                                    </a>
                                <?php else: ?>
                                    <span>No download available</span>
                                <?php endif; ?>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>No chapters available for this book.</p>
                <?php endif; ?>
            </div>

            <!-- User Reviews Section -->
            <div class="user-reviews">
                <!-- Review Submission Form -->
                <form style="display:flex; flex-direction:column;" action="review.php?book_id=<?php echo $book_id; ?>" method="POST">
                    
                    <!-- Star Rating System -->
                    <div class="rating">
                        <label for="rating"><b>Rating:</b> </label>
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                    </div>
            
                    <!-- Hidden Review Textarea -->
                    <textarea name="review_content" id="review_content" placeholder="Write your review here..." style="display:none;"></textarea>
            
                    <!-- Hidden Rating Input -->
                    <input type="hidden" name="rating" id="rating" value="">
            
                    <!-- Submit Button -->
                    <button type="submit" id="button" name="button">Submit Review</button>
                </form>

                <h2>User Reviews</h2>
            
                <?php
                $reviewQuery = "SELECT * FROM reviews WHERE book_id = $book_id";
                $reviewResult = $connection->query($reviewQuery);
            
                if ($reviewResult->num_rows > 0) {
                    while ($review = $reviewResult->fetch_assoc()) {
                        echo '<div class="review">';
                        echo '<p><strong>' . htmlspecialchars($review['username']) . ':</strong> ' . htmlspecialchars($review['review']) . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No reviews yet. Be the first to review!</p>';
                }
                ?>
            </div>
        </section>
    </main>

    <script>
        const stars = document.querySelectorAll('.star');
        const reviewContent = document.getElementById('review_content');
        const ratingInput = document.getElementById('rating');
    
        stars.forEach(star => {
            star.addEventListener('click', () => {
                const rating = star.getAttribute('data-value');
                ratingInput.value = rating; // Set the hidden input value to the clicked star rating
                reviewContent.style.display = 'block'; // Show the review textarea when a rating is selected
            });
        });
    </script>

    <!-- Related Books Section -->
    <section class="section_related_books">
            <?php
            // Fetch related books based on genre
            $relatedQuery = "SELECT * FROM books WHERE CATEGORY = '" . $connection->real_escape_string($book['CATEGORY']) . "' AND book_id != $book_id LIMIT 5";
            $relatedResult = $connection->query($relatedQuery);
            ?>

            <?php
            if ($relatedResult->num_rows > 0) {
                echo '<h2>Related Books</h2>';
                echo '<div class="related-books">';
                while ($relatedBook = $relatedResult->fetch_assoc()) {
                    echo '<div class="related-book-item">';
                    echo '<div class="book-coverpage">';
                    echo '<a href="bookinfo.php?book_id=' . $relatedBook['book_id'] . '"><img src="img/Book_Covers/' . htmlspecialchars($relatedBook['BOOK_COVER']) . '" alt="' . htmlspecialchars($relatedBook['TITLE']) . ' cover"></a>';
                    echo '</div>';
                    echo '<div class="link">';
                    echo '<a href="bookinfo.php?book_id=' . $relatedBook['book_id'] . '">' . htmlspecialchars($relatedBook['TITLE']) . '</a>';
                    echo '</div>';
                    echo '</div>';
                }
                echo '</div>';
            } 
            //else {
            //     echo '<h2>Related Books</h2>';
            //     echo '<div class="related-books">';
            //         echo '<p>No related books found.</p>';
            //     echo '</div>';
            // }
            ?>
    </section>

    <!-- Footer -->
    <footer>
        <?php include 'footer.php'; ?>
    </footer>

    <script>
        let isBookmarked = false; // Track bookmark state

        // Check bookmark state on page load (this could be implemented in PHP)
        window.onload = function() {
            fetch(`check_bookmark.php?book_id=<?php echo $book_id; ?>&username=<?php echo $_SESSION['username']; ?>`)
                .then(response => response.json())
                .then(data => {
                    if (data.isBookmarked) {
                        isBookmarked = true;
                        document.getElementById('bookmark-button').querySelector('img').src = "img/fontawesome-free-6.6.0-web/svgs/bookmark/Black-bookmark.svg"; // Set to black colored
                    }
                })
                .catch(error => console.error('Error:', error));
        };


        function toggleBookmark() {
            const bookId = <?php echo $book_id; ?>;

            fetch('bookmark.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        'book_id': bookId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const icon = document.getElementById('bookmark-button').querySelector('img');
                        if (data.bookmarked) {
                            // alert('Bookmarked successfully!');
                            icon.src = "img/fontawesome-free-6.6.0-web/svgs/bookmark/Black-bookmark.svg"; // Bookmarked icon
                        } else {
                            // alert('UnBookmark successfully!');
                            icon.src = "img/fontawesome-free-6.6.0-web/svgs/bookmark/Clear-bookmark.svg"; // Unbookmarked icon
                        }
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>

</body>

</html>