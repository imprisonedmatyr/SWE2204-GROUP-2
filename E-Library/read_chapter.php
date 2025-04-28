<?php
session_start();

require 'db_connect.php';
require 'vendor/autoload.php';

use Smalot\PdfParser\Parser;

$chapter = null;
$book = null;

// Check if a specific chapter is requested
if (isset($_GET['chapter_id'])) {
    $chapter_id = intval($_GET['chapter_id']);

    // Fetch the chapter details
    $title = "SELECT * FROM content WHERE ChapterID = $chapter_id";
    $result = $database->query($title);

    if ($result && $result->num_rows > 0) {
        $chapter = $result->fetch_assoc();
        $book_id = $chapter['book_id'];

        // Fetch the corresponding book details
        $bookQuery = "SELECT * FROM books WHERE book_id = $book_id";
        $bookResult = $database->query($bookQuery);

        if ($bookResult && $bookResult->num_rows > 0) {
            $book = $bookResult->fetch_assoc();
        } else {
            echo "<div class='error-message'>Book not found.</div>";
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
                echo "<div class='error-message'>Unsupported file format.</div>";
                exit;
            }
        } else {
            echo "<div class='error-message'>Chapter file not found at: " . htmlspecialchars($file_path) . "</div>";
            exit;
        }
    } else {
        echo "<div class='error-message'>Chapter not found for ID: " . htmlspecialchars($chapter_id) . "</div>";
        exit;
    }
} else {
    echo "<div class='error-message'>No chapter selected.</div>";
    exit;
}

// Fetch all chapters for navigation
$chapterQuery = "SELECT * FROM content WHERE book_id = $book_id ORDER BY ChapterID";
$chaptersResult = $database->query($chapterQuery);

// Find previous and next chapters
$prevChapter = null;
$nextChapter = null;
$chaptersArray = [];

if ($chaptersResult && $chaptersResult->num_rows > 0) {
    while ($row = $chaptersResult->fetch_assoc()) {
        $chaptersArray[] = $row;
    }
    
    // Reset result pointer
    $chaptersResult->data_seek(0);
    
    for ($i = 0; $i < count($chaptersArray); $i++) {
        if ($chaptersArray[$i]['ChapterID'] == $chapter_id) {
            if ($i > 0) {
                $prevChapter = $chaptersArray[$i - 1];
            }
            if ($i < count($chaptersArray) - 1) {
                $nextChapter = $chaptersArray[$i + 1];
            }
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['TITLE']); ?> - <?php echo htmlspecialchars($chapter['Chapter_title']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/read_chapter.css?v=<?php echo time(); ?>">
    <link rel="icon" type="image/x-icon" href="img/favicon/sk.ico">
</head>

<body>
    <header>
        <?php include 'header_client.php'; ?>
    </header>

    <main>
        <div class="reading-container">
            <div class="reading-toolbar">
                <div class="toolbar-actions">
                    <button id="font-size-decrease" title="Decrease font size"><i class="fas fa-font fa-sm"></i></button>
                    <button id="font-size-increase" title="Increase font size"><i class="fas fa-font fa-lg"></i></button>
                    <button id="toggle-dark-mode" title="Toggle dark mode"><i class="fas fa-moon"></i></button>
                    <button id="toggle-line-spacing" title="Adjust line spacing"><i class="fas fa-text-height"></i></button>
                </div>
                <div class="book-details">
                    <h2><?php echo htmlspecialchars($book['TITLE']); ?></h2>
                </div>
            </div>

            <div class="chapter-navigation-bar">
                <?php if ($prevChapter): ?>
                <a href="read_chapter.php?chapter_id=<?php echo $prevChapter['ChapterID']; ?>" class="nav-button prev-button">
                    <i class="fas fa-chevron-left"></i> Previous Chapter
                </a>
                <?php else: ?>
                <span class="nav-button prev-button disabled">
                    <i class="fas fa-chevron-left"></i> Previous Chapter
                </span>
                <?php endif; ?>

                <button id="chapter-dropdown-toggle" class="chapter-dropdown-toggle">
                    Chapter <?php echo htmlspecialchars($chapter['ChapterID']); ?>: <?php echo htmlspecialchars($chapter['Chapter_title']); ?>
                    <i class="fas fa-caret-down"></i>
                </button>

                <?php if ($nextChapter): ?>
                <a href="read_chapter.php?chapter_id=<?php echo $nextChapter['ChapterID']; ?>" class="nav-button next-button">
                    Next Chapter <i class="fas fa-chevron-right"></i>
                </a>
                <?php else: ?>
                <span class="nav-button next-button disabled">
                    Next Chapter <i class="fas fa-chevron-right"></i>
                </span>
                <?php endif; ?>
            </div>

            <div id="chapter-dropdown" class="chapter-dropdown">
                <div class="dropdown-header">
                    <h3>Chapters</h3>
                    <button id="close-dropdown"><i class="fas fa-times"></i></button>
                </div>
                <ul>
                    <?php 
                    $chaptersResult->data_seek(0);
                    while ($navChapter = $chaptersResult->fetch_assoc()): 
                        $isCurrentChapter = ($navChapter['ChapterID'] == $chapter_id);
                    ?>
                    <li class="<?php echo $isCurrentChapter ? 'current-chapter' : ''; ?>">
                        <a href="read_chapter.php?chapter_id=<?php echo $navChapter['ChapterID']; ?>">
                            Chapter <?php echo htmlspecialchars($navChapter['ChapterID']); ?>: 
                            <?php echo htmlspecialchars($navChapter['Chapter_title']); ?>
                        </a>
                    </li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <section class="chapter-content-section">
                <div class="chapter-header">
                    <h1>Chapter <?php echo htmlspecialchars($chapter['ChapterID']); ?>: <?php echo htmlspecialchars($chapter['Chapter_title']); ?></h1>
                </div>
                <div class="chapter-text" id="chapter-text">
                    <?php echo $chapter_content; ?>
                </div>
                <div class="chapter-footer">
                    <div class="chapter-navigation-buttons">
                        <?php if ($prevChapter): ?>
                        <a href="read_chapter.php?chapter_id=<?php echo $prevChapter['ChapterID']; ?>" class="nav-button prev-button">
                            <i class="fas fa-chevron-left"></i> Previous Chapter
                        </a>
                        <?php endif; ?>

                        <?php if ($nextChapter): ?>
                        <a href="read_chapter.php?chapter_id=<?php echo $nextChapter['ChapterID']; ?>" class="nav-button next-button">
                            Next Chapter <i class="fas fa-chevron-right"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <div class="progress-indicator" id="progress-indicator"></div>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>

    <script>
        // Reading progress indicator
        window.addEventListener('scroll', function() {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            document.getElementById('progress-indicator').style.width = scrolled + '%';
        });

        // Chapter dropdown functionality
        const chapterDropdownToggle = document.getElementById('chapter-dropdown-toggle');
        const chapterDropdown = document.getElementById('chapter-dropdown');
        const closeDropdown = document.getElementById('close-dropdown');

        chapterDropdownToggle.addEventListener('click', function() {
            chapterDropdown.classList.toggle('active');
        });

        closeDropdown.addEventListener('click', function() {
            chapterDropdown.classList.remove('active');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!chapterDropdown.contains(event.target) && !chapterDropdownToggle.contains(event.target)) {
                chapterDropdown.classList.remove('active');
            }
        });

        // Reading preferences functionality
        const chapterText = document.getElementById('chapter-text');
        const fontSizeDecrease = document.getElementById('font-size-decrease');
        const fontSizeIncrease = document.getElementById('font-size-increase');
        const toggleDarkMode = document.getElementById('toggle-dark-mode');
        const toggleLineSpacing = document.getElementById('toggle-line-spacing');

        // Load user preferences
        let fontSize = localStorage.getItem('fontSize') || 18;
        let darkMode = localStorage.getItem('darkMode') === 'true';
        let lineSpacing = localStorage.getItem('lineSpacing') || 1.6;

        // Apply saved preferences
        applyPreferences();

        // Font size controls
        fontSizeDecrease.addEventListener('click', function() {
            if (fontSize > 14) {
                fontSize -= 2;
                applyPreferences();
            }
        });

        fontSizeIncrease.addEventListener('click', function() {
            if (fontSize < 30) {
                fontSize += 2;
                applyPreferences();
            }
        });

        // Dark mode toggle
        toggleDarkMode.addEventListener('click', function() {
            darkMode = !darkMode;
            applyPreferences();
        });

        // Line spacing toggle
        toggleLineSpacing.addEventListener('click', function() {
            const spacingOptions = [1.6, 2.0, 2.4, 1.2];
            const currentIndex = spacingOptions.indexOf(parseFloat(lineSpacing));
            const nextIndex = (currentIndex + 1) % spacingOptions.length;
            lineSpacing = spacingOptions[nextIndex];
            applyPreferences();
        });

        function applyPreferences() {
            // Apply font size
            chapterText.style.fontSize = fontSize + 'px';
            
            // Apply dark mode
            if (darkMode) {
                document.body.classList.add('dark-mode');
                toggleDarkMode.innerHTML = '<i class="fas fa-sun"></i>';
            } else {
                document.body.classList.remove('dark-mode');
                toggleDarkMode.innerHTML = '<i class="fas fa-moon"></i>';
            }
            
            // Apply line spacing
            chapterText.style.lineHeight = lineSpacing;
            
            // Save preferences
            localStorage.setItem('fontSize', fontSize);
            localStorage.setItem('darkMode', darkMode);
            localStorage.setItem('lineSpacing', lineSpacing);
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            <?php if ($prevChapter): ?>
            if (e.key === 'ArrowLeft') {
                window.location.href = 'read_chapter.php?chapter_id=<?php echo $prevChapter['ChapterID']; ?>';
            }
            <?php endif; ?>
            
            <?php if ($nextChapter): ?>
            if (e.key === 'ArrowRight') {
                window.location.href = 'read_chapter.php?chapter_id=<?php echo $nextChapter['ChapterID']; ?>';
            }
            <?php endif; ?>
        });
    </script>
</body>
</html>