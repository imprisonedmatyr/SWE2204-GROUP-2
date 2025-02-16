<?php
session_start();
require 'db_connect.php';

$response = ['isBookmarked' => false];

if (isset($_GET['book_id']) && isset($_SESSION['username'])) {
    $book_id = intval($_GET['book_id']);
    $username = $_SESSION['username'];

    $checkQuery = "SELECT * FROM favorite_books WHERE book_id = ? AND username = ?";
    $stmt = $connection->prepare($checkQuery);
    $stmt->bind_param('is', $book_id, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response['isBookmarked'] = true;
    }
}

echo json_encode($response);
