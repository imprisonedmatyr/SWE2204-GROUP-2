<?php
session_start();
require 'db_connect.php';

$response = ['success' => false, 'message' => ''];

if (isset($_POST['book_id']) && isset($_SESSION['username'])) {
    $book_id = intval($_POST['book_id']);
    $username = $_SESSION['username'];

    // Check if the book is already bookmarked
    $checkQuery = "SELECT * FROM favorite_books WHERE book_id = ? AND username = ?";
    $stmt = $connection->prepare($checkQuery);
    $stmt->bind_param('is', $book_id, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Book not yet bookmarked; insert into favorite_books
        $insertQuery = "INSERT INTO favorite_books (book_id, username) VALUES (?, ?)";
        $stmt = $connection->prepare($insertQuery);
        $stmt->bind_param('is', $book_id, $username);
        if ($stmt->execute()) {
            $response = ['success' => true, 'bookmarked' => true];
        } else {
            $response['message'] = 'Error bookmarking book';
        }
    } else {
        // Book is already bookmarked; remove from favorite_books
        $deleteQuery = "DELETE FROM favorite_books WHERE book_id = ? AND username = ?";
        $stmt = $connection->prepare($deleteQuery);
        $stmt->bind_param('is', $book_id, $username);
        if ($stmt->execute()) {
            $response = ['success' => true, 'bookmarked' => false];
        } else {
            $response['message'] = 'Error unbookmarking book';
        }
    }
} else {
    $response['message'] = 'Invalid request';
}

echo json_encode($response);
