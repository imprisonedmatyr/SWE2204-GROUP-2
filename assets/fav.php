<?php
session_start();
include 'db_connection.php';

// Check if user is logged in
if (isset($_SESSION['username']) && isset($_GET['book_id'])) {
    $username = $_SESSION['username'];
    $book_id = $_GET['book_id'];

    if (isset($_POST['bookmark'])) {
        // Check if the book is already bookmarked
        $title = "SELECT COUNT(*) FROM favorite_books WHERE book_id = ? AND username = ?";
        if ($stmt = $connection->prepare($title)) {
            $stmt->bind_param('is', $book_id, $username);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                // Remove the bookmark (unfavorite the book)
                $deleteQuery = "DELETE FROM favorite_books WHERE book_id = ? AND username = ?";
                if ($deleteStmt = $connection->prepare($deleteQuery)) {
                    $deleteStmt->bind_param('is', $book_id, $username);
                    if ($deleteStmt->execute()) {
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Failed to remove from favorites']);
                    }
                    $deleteStmt->close();
                }
            } else {
                // Add the bookmark (favorite the book)
                $insertQuery = "INSERT INTO favorite_books (book_id, username) VALUES (?, ?)";
                if ($insertStmt = $connection->prepare($insertQuery)) {
                    $insertStmt->bind_param('is', $book_id, $username);
                    if ($insertStmt->execute()) {
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Failed to add to favorites']);
                    }
                    $insertStmt->close();
                }
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Database query failed']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'User not logged in or missing book ID']);
}
?>