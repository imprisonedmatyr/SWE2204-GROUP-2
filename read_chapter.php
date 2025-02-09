<?php
session_start();

require 'db_connect.php'; // require 'db_connect.php' database connection file
require 'vendor/autoload.php'; // Include PDF Parser library

use Smalot\PdfParser\Parser;

$chapter = null;
$book = null;

// Check if a specific chapter is requested
if (isset($_GET['chapter_id'])) {
    $chapter_id = intval($_GET['chapter_id']);

    // Fetch the chapter details
    $title = "SELECT * FROM content WHERE ChapterID = $chapter_id";
    $result = $connection->query($title);

    if ($result && $result->num_rows > 0) {
        $chapter = $result->fetch_assoc();
        $book_id = $chapter['book_id'];

        // Fetch the corresponding book details
        $bookQuery = "SELECT * FROM books WHERE book_id = $book_id";
        $bookResult = $connection->query($bookQuery);

        if ($bookResult && $bookResult->num_rows > 0) {
            $book = $bookResult->fetch_assoc();
        } else {
            echo "Book not found.";
            exit;
        }

        // fetch filepath from stored location 
        $file_path = "chapters/" . $chapter['File_path'];

        // check file path exists
        if (file_exists($file_path)) {
            $file_extension = pathinfo($file_path, PATHINFO_EXTENSION);

            if ($file_extension === 'txt') {
                // Handle text files
                $chapter_content = file_get_contents($file_path);
                $chapter_content = nl2br(htmlspecialchars($chapter_content));
            } elseif ($file_extension === 'pdf') {
                // Handle PDF files
                $parser = new Parser();
                $pdf = $parser->parseFile($file_path);
                $chapter_content = nl2br(htmlspecialchars($pdf->getText()));

            } else {
                echo "Unsupported file format.";
                exit;
            }
        } else {
            echo "Chapter file not found at: " . htmlspecialchars($file_path);
            exit;
        }
    } else {
        echo "Chapter not found for ID: " . htmlspecialchars($chapter_id);
        exit;
    }
} else {
    echo "No chapter selected.";
    exit;
}

// Fetch all chapters for navigation
$chapterQuery = "SELECT * FROM content WHERE book_id = $book_id ORDER BY Chapter_title";
$chaptersResult = $connection->query($chapterQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['TITLE']); ?> - <?php echo htmlspecialchars($chapter['Chapter_title']); ?>
    </title>
    <link rel="stylesheet" href="css/read_chapter.css?v=<?php time(); ?>">
    <link rel="icon" type="image/x-icon" href="img/favicon/sk.ico">
</head>

<body>
    <header>
        <?php include 'header_client.php'; ?>
    </header>

    <main>
        <section class="chapter-content-section">
            <h1><?php echo htmlspecialchars($book['TITLE']); ?> -
                <?php echo htmlspecialchars($chapter['Chapter_title']); ?>
            </h1>
            <div class="chapter-text">
                <?php
                // Output the chapter content
                echo $chapter_content;
                ?>
            </div>
            <?php if($chaptersResult->num_rows > 0): ?>
            <div class="chapter-navigation">
                <h2>Navigate to Other Chapters</h2>
                <ul class="chapter-list">
                    <?php while ($navChapter = $chaptersResult->fetch_assoc()): ?>
                        <li>
                            <a href="read_chapter.php?chapter_id=<?php echo $navChapter['ChapterID']; ?>">
                                Chapter
                                
                                <?php echo htmlspecialchars($navChapter['ChapterID']) . ': ' . htmlspecialchars($navChapter['Chapter_title']); ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
</body>

</html>