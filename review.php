<?php
session_start();

require 'db_connect.php';

$book_id = $_SESSION['book_id'] ?? $_GET['book_id'] ?? 0;  // Assuming book_id is stored in session or passed via URL

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['review_content'])) {
    $review_content = trim($_POST['review_content']);
    if (!empty($review_content) && isset($_SESSION['username'])) {
        $title = 'INSERT INTO reviewS (book_id, username, review) VALUES (?, ?, ?)';
        $stmt = $connection->prepare($title);
        $stmt->bind_param('iss', $book_id, $_SESSION['username'], $review_content);
        if ($stmt->execute()) {
            header("Location: bookinfo.php?book_id=" . $book_id);
            exit;
        }
    }
}
