<?php
session_start();
require 'db_connect.php';

if (isset($_GET['book_id'])) {
    $book_id = intval($_GET['book_id']);
    // Prepare statement to fetch chapters for the selected book
    $stmt = $connection->prepare("SELECT chapterid, Chapter_title FROM CONTENT WHERE book_id = ?");
    if (!$stmt) {
        
        $_SESSION['error_message'] = 'Error preparing statement: ' . htmlspecialchars($connection->error);
        echo json_encode([]);
        exit;
    }
    
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $chapters = [];
    while ($row = $result->fetch_assoc()) {

        $chapters[] = [
            'chapter_id' => $row['chapterid'], // Assuming you have chapter_id in CONTENT
            'chapter_title' => htmlspecialchars($row['Chapter_title']) // Assuming chapter_title exists in your database
        ];
    }
    
    // Return chapters as JSON
    echo json_encode($chapters);
    
    $stmt->close();
} else {
    echo 'nope';
    $_SESSION['error_message'] = "No book ID provided.";
}

$connection->close();
?>