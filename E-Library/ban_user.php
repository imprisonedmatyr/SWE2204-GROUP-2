<?php
session_start();
require 'db_connect.php'; // Make sure to include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID from the request
    $data = json_decode(file_get_contents("php://input"), true);
    $user_id = intval($data['user_id']);

    // Update the user's status to banned
    $stmt = $connection->prepare("UPDATE users SET is_banned = 1 WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'User banned successfully.']);
    } else {
        echo json_encode(['message' => 'Failed to ban user.']);
    }

    $stmt->close();
    $connection->close();
} else {
    echo json_encode(['message' => 'Invalid request method.']);
}
?>
