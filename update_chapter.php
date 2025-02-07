<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chapter_id = intval($_POST['chapter_id']); // chapter_id passed in selected options of the select form element
    $chapter_title = htmlspecialchars(trim($_POST['chapter_title']));
    $book_id = intval($_POST['book_id']);
    $upload_dir = "/VL/chapters/";

    $file_path = '';

    // Check for file upload
    if (isset($_FILES['chapter_content']) && $_FILES['chapter_content']['error'] === UPLOAD_ERR_OK) {
        $file_name = basename($_FILES['chapter_content']['name']);
        $file_path = $upload_dir . uniqid() . '_' . $file_name;

        $allowed_types = ['application/pdf', 'text/plain', 'application/epub+zip'];
        if (!in_array($_FILES['chapter_content']['type'], $allowed_types)) {
            $_SESSION['error_message'] = "Invalid file type. Only PDF, TXT files are allowed.";
            header("Location: managecatalog.php");
            exit;
        }

        if (!move_uploaded_file($_FILES['chapter_content']['tmp_name'], $file_path)) {
            $_SESSION['error_message'] = "Failed to move uploaded file.";
            header("Location: managecatalog.php");
            exit;
        }
    }

    // Prepare update SQL statement
    $stmt = $connection->prepare("UPDATE CONTENT SET Chapter_title = ?, File_path = ? WHERE chapter_id = ?");
    if ($stmt) {
        $stmt->bind_param("ssi", $chapter_title, $file_path, $chapter_id);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Chapter updated successfully.";
        } else {
            $_SESSION['error_message'] = "Error executing statement: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['error_message'] = "Error preparing statement: " . $connection->error;
    }

    $connection->close();
    header("Location: managecatalog.php");
    exit;
}
header("Location: managecatalog.php");
exit;
