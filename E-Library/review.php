<?php
session_start();

require 'db_connect.php';

$book_id = $_SESSION['book_id'] ?? $_GET['book_id'] ?? 0;

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['username'])) {
    $review_content = trim($_POST['review_content']);
    $rating = $_POST['rating'];
    $title = 'INSERT INTO reviewS (book_id, username, rating, review) VALUES (?, ?, ?, ?)';
    $stmt = $connection->prepare($title);
    $stmt->bind_param('isis', $book_id, $_SESSION['username'], $rating, $review_content);
    if(!empty($rating) && $_POST['rating']){
        if ($stmt->execute()) {
            header("Location: bookinfo.php?book_id=" . $book_id);
            exit;
        }
    }
}else{
    header("Location: signIn.php");
    exit;
}
