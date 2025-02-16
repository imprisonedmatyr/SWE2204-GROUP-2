<?php
require 'db_connect.php';
// Initialize search parameters
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $author = isset($_POST['author']) ? $_POST['author'] : '';
    $genre = isset($_POST['genre']) ? $_POST['genre'] : '';

    // Build the SQL query for fetching appropriate books from the database
    $sql = "";
    $exe = null;
    $result = null;

    // Filter by title
    if (!empty($title) && (empty($author) && empty($genre))) {
        $sql = "SELECT book_id, TITLE, AUTHOR, `BOOK_COVER` AS IMAGE FROM BOOKS WHERE TITLE LIKE ?";
        $exe = $connection->prepare($sql);
        $t = '%' . $title . '%';
        $exe->bind_param("s", $t);
        $exe->execute();
        $result = $exe->get_result();
        returnData($result);
    }

    // Filter by author
    elseif (!empty($author) && (empty($genre) && empty($title))) {
        $sql = "SELECT book_id, TITLE, AUTHOR, `BOOK_COVER` AS IMAGE FROM BOOKS WHERE AUTHOR LIKE ?";
        $exe = $connection->prepare($sql);
        $a = '%' . $author . '%';
        $exe->bind_param("s", $a);
        $exe->execute();
        $result = $exe->get_result();
        returnData($result);
    }

    // Filter by category name
    elseif (!empty($genre) && (empty($author) && empty($title))) {
        $sql = "SELECT book_id, TITLE, AUTHOR, `BOOK_COVER` AS IMAGE FROM BOOKS WHERE CATEGORY = ?";
        $exe = $connection->prepare($sql);
        $exe->bind_param("s", $genre);
        $exe->execute();
        $result = $exe->get_result();
        returnData($result);
    } else {
        echo json_encode(['message' => 'No books found']);
    }
}
function returnData($result)
{
    if ($result && $result->num_rows > 0) {
        $books = [];
        while ($row = $result->fetch_assoc()) {
            $books[] = [
                'book_id' => $row['book_id'],
                'TITLE' => $row['TITLE'],
                'AUTHOR' => $row['AUTHOR'],
                'IMAGE' => $row['IMAGE']
            ];
        }
        header('Content-Type: application/json');  // Sending response in JSON format.
        echo json_encode($books);
        exit;  // End the script after sending the response.
    } else {
        echo json_encode(['message' => 'No books found']);
    }
    $result->close();
}
