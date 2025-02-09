<?php
require 'db_connect.php';

// Constants for upload directories and allowed file types
define('UPLOAD_DIR', '/xampp/htdocschapters/');
$allowed_types = ['application/pdf', 'text/plain', 'application/epub+zip'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chapter_title = htmlspecialchars(trim($_POST['chapter_title']));
    $book_id = intval($_POST['book_id']);

    // Handle chapter content file upload
    // Create upload directory if it doesn't exist
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0777, true);
    }

    // Construct upload path
    $file_name = basename($_FILES['chapter_content']['name']);
    $file_path = UPLOAD_DIR . $file_name;

    // Check if uploaded file exists
    if (empty($_FILES['chapter_content']['name'])) {
        $_SESSION['error_message'] = "No file uploaded.";
        include "managecatalog.php";
        exit;
    }

    // Checking file upload success or error
    if ($_FILES['chapter_content']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error_message'] = "Error uploading chapter.";
        include "managecatalog.php";
        exit;
    }

    // Validate file type
    if (!in_array($_FILES['chapter_content']['type'], $allowed_types)) {
        $_SESSION['error_message'] = "Invalid file type. Only PDF and TXT files are allowed.";
        include "managecatalog.php";
        exit;
    }

    // Check file size (limit to 1MB)
    if ($_FILES['chapter_content']['size'] > 20971520) {
        $_SESSION['error_message'] = "File is too large.";
        include "managecatalog.php";
        exit;
    }

    // Attempt to move the uploaded file
    if (!move_uploaded_file($_FILES['chapter_content']['tmp_name'], $file_path)) {
        $_SESSION['error_message'] = "Failed to move uploaded file.";
        include "managecatalog.php";
        exit;
    }

    // Prepare the SQL statement for inserting chapter details
    try{
        $stmt = $connection->prepare("INSERT INTO CONTENT (Chapter_title, File_path, book_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $chapter_title, $file_name, $book_id);
        $stmt->execute();
        
        $stmt->close();
        $_SESSION['success_message'] = "Uploaded successfully.";
        header("Location: managecatalog.php");
        exit;

    }catch(Exception $e){
        $_SESSION["error_message"] = $e->getMessage();
        header("Location: managecatalog.php");
        exit;
    }
}
$connection->close();
?>