<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user input...
    $title = htmlspecialchars(trim($_POST['title']));
    $author = htmlspecialchars(trim($_POST['author']));
    $category = htmlspecialchars(trim($_POST['category']));
    $genre = htmlspecialchars(trim($_POST['genre']));
    $publication_year = intval($_POST['year']);
    $status = "Unavailable";
    
    // Begin transaction
    $connection->begin_transaction();
    
    try {
        // Handle file uploads...
        $upload_dir_summary = "/xampp/htdocs/VL/Summary/";
        $upload_dir_cover = "/xampp/htdocs/VL/img/Book_Covers/";
        
        if (!is_dir($upload_dir_summary)) {
            mkdir($upload_dir_summary, 0777, true);
        }
        if (!is_dir($upload_dir_cover)) {
            mkdir($upload_dir_cover, 0777, true);
        }
        $summary_file_name = basename($_FILES['summary']['name']);
        $summary_file_path = $upload_dir_summary . $summary_file_name;
        
        if ($_FILES['summary']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error_message'] = "Error uploading summary file.";
            include "managecatalog.php";
            exit;
        }
        $allowed_summary_types = ['application/pdf', 'text/plain', 'application/epub+zip'];
        if (!in_array($_FILES['summary']['type'], $allowed_summary_types)) {
            $_SESSION['error_message'] = "Invalid file type for summary. Only PDF, TXT, and EPUB files are allowed.";
            include 'managecatalog.php';
            exit;
        }
        if ($_FILES['summary']['size'] > 1048576) { // Limit to 1MB
            $_SESSION['error_message'] = "Summary file is too large.";
            include 'managecatalog.php';
            exit;
        }

        if (!move_uploaded_file($_FILES['summary']['tmp_name'], $summary_file_path)) {
            $_SESSION['error_message'] = "Failed to upload summary file.";
            include 'managecatalog.php'; // Include the page to show feedback without redirecting.
            exit;
        }

        // Handle book cover file
        $book_cover_name = basename($_FILES['book_cover']['name']);
        $book_cover_path = $upload_dir_cover . $book_cover_name;

        $allowed_cover_types = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
        $cover_mime_type = mime_content_type($_FILES['book_cover']['tmp_name']);

        if ($_FILES['book_cover']['error'] !== UPLOAD_ERR_OK || !in_array($cover_mime_type, $allowed_cover_types)) {
            $_SESSION['error_message'] = "Invalid book cover upload. Only JPG, PNG, and GIF images are allowed.";
            include 'managecatalog.php';
            exit;
        }

        if (!move_uploaded_file($_FILES['book_cover']['tmp_name'], $book_cover_path)) {
            $_SESSION['error_message'] = "Failed to upload book cover.";
            include 'managecatalog.php';
            exit;
        }
        // Insert into database...
        $stmt = $connection->prepare("INSERT INTO books (TITLE, AUTHOR, `BOOK_COVER`, STATUS, CATEGORY, GENRE, `PUBLICATION YEAR`, short_description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        // Bind parameters...
        $stmt->bind_param("ssssssis", $title, $author, $book_cover_name, $status, $category, $genre, $publication_year, $summary_file_name);
        //execute query
        $stmt->execute();
        
        // Commit transaction
        $connection->commit();
        $_SESSION['success_message'] = "Book added successfully!";
        
    } catch (Exception $e) {
        $connection->rollback();
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
    }
    
    $connection->close();
    header("Location: managecatalog.php");
    exit;
}

?>