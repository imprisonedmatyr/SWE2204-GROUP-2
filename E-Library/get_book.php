<?php
require 'db_connect.php';

$book_id = $_GET['book_id'];
$stmt = $connection->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->bind_param("s", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(["error" => "Book not found"]);
}
?>
