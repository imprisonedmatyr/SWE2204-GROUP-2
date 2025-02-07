<?php
require 'db_connect.php';

$book_id = $_GET['book_id'];

$stmt = $connection->prepare("DELETE FROM books WHERE book_id = ?");
$stmt->bind_param("s", $book_id);

$response = ["success" => false];

if ($stmt->execute()) {
    $response["success"] = true;
}

echo json_encode($response);
?>
