<?php
session_start();
require 'db_connect.php';

// Constants for upload directories
define('UPLOAD_DIR', '/opt/lampp/htdocs/VL/img/Book_Covers/');
define('SUMMARY_UPLOAD_DIR', '/opt/lampp/htdocs/VL/Summary/');
//Constants for allowed file types
$allowed_types = ['image/jpg','image/jpeg', 'image/png', 'image/gif'];
$allowed_summary_types = ['application/pdf', 'text/plain', 'application/epub+zip'];

// Function to fetch all books
function fetchAllBooks($connection) {
    $stmt = $connection->prepare('SELECT * FROM BOOKS ORDER BY book_id DESC');
    if (!$stmt) {
        throw new Exception('Error preparing statement: ' . htmlspecialchars($connection->error));
    }
    $stmt->execute();
    return $stmt->get_result();
}

// Ensure the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate user input
    $book_id = intval($_POST['book_id_update']);
    $title = htmlspecialchars(trim($_POST['title']));
    $author = htmlspecialchars(trim($_POST['author']));
    $category = htmlspecialchars(trim($_POST['category']));
    $genre = htmlspecialchars(trim($_POST['genre']));
    $publication_year = intval($_POST['year']);

    // Check for required fields
    if (empty($title) || empty($author) || empty($category) || empty($genre) || $publication_year < 1000 || $publication_year > 9999) {
        $_SESSION['error_message'] = 'Please fill in all required fields correctly.';
        header("Location: managecatalog.php");
        exit;
    }

    // Initialize variables for file uploads
    $book_cover = '';
    $summary_file_name = '';

    // Handle book cover file upload
    if (!empty($_FILES['book_cover']['name'])) {
        if ($_FILES['book_cover']['error'] === UPLOAD_ERR_OK) {
            $file_type = mime_content_type($_FILES['book_cover']['tmp_name']);
            if (in_array($file_type, $allowed_types)) {
                $book_cover = uniqid() . '_' . basename($_FILES['book_cover']['name']);
                $upload_path = UPLOAD_DIR . $book_cover;

                // Attempt to move the uploaded file
                if (!move_uploaded_file($_FILES['book_cover']['tmp_name'], $upload_path)) {
                    $_SESSION['error_message'] = 'Error uploading book cover. Please ensure the file is valid.';
                    header("Location: managecatalog.php");
                    exit;
                }
            } else {
                $_SESSION['error_message'] = 'Invalid book cover file type. Please upload a valid image (JPG, PNG, GIF).';
                header("Location: managecatalog.php");
                exit;
            }
        }
    } else {
        // Fetch current book cover from database if no new cover is provided
        $pst = $connection->prepare("SELECT `BOOK_COVER` FROM BOOKS WHERE book_id = ?");
        if (!$pst) {
            $_SESSION['error_message'] = 'Error preparing statement: ' . htmlspecialchars($connection->error);
            header("Location: managecatalog.php");
            exit;
        }
        $pst->bind_param('i', $book_id);
        $pst->execute();
        $result = $pst->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $book_cover = $row['BOOK COVER'];
        }
        $pst->close();
    }

    // Handle summary file upload
    if (!empty($_FILES['summary']['name'])) {
        if ($_FILES['summary']['error'] === UPLOAD_ERR_OK) {
            $summary_file_type = mime_content_type($_FILES['summary']['tmp_name']);
            if (in_array($summary_file_type, $allowed_summary_types)) {
                $summary_file_name = uniqid() . '_' . basename($_FILES['summary']['name']);
                $summary_upload_path = SUMMARY_UPLOAD_DIR . $summary_file_name;

                // Attempt to move the uploaded summary file
                if (!move_uploaded_file($_FILES['summary']['tmp_name'], $summary_upload_path)) {
                    $_SESSION['error_message'] = 'Error uploading summary file. Please ensure the file is valid.';
                    header("Location: managecatalog.php");
                    exit;
                }
            } else {
                $_SESSION['error_message'] = 'Invalid file type. Please upload a valid PDF, TXT file.';
                header("Location: managecatalog.php");
                exit;
            }
        }
    } else {
        // Fetch current summary from database if no new summary is provided
        $pst = $connection->prepare("SELECT short_description FROM BOOKS WHERE book_id = ?");
        if (!$pst) {
            $_SESSION['error_message'] = 'Error preparing statement: ' . htmlspecialchars($connection->error);
            header("Location: managecatalog.php");
            exit;
        }
        $pst->bind_param('i', $book_id);
        $pst->execute();
        $result = $pst->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $summary_file_name = htmlspecialchars($row["short_description"]);
        }
        $pst->close();
    }

    // Prepare the SQL statement for updating the book details
    try {
        $stmt = $connection->prepare("UPDATE BOOKS SET TITLE = ?, AUTHOR = ?, `BOOK_COVER` = ?, CATEGORY = ?, GENRE = ?, `PUBLICATION YEAR` = ?, short_description = ? WHERE book_id = ?");
        
        if ($stmt === false) {
            throw new Exception('Error preparing statement: ' . htmlspecialchars($connection->error));
        }

        // Bind parameters and execute the statement
        if ($stmt->bind_param("sssssisi", $title, $author, $book_cover, $category, $genre, $publication_year, $summary_file_name, $book_id)) {
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Book updated successfully!';
            } else {
                throw new Exception('Error executing update: ' . htmlspecialchars($stmt->error));
            }
        } else {
            throw new Exception('Error binding parameters: ' . htmlspecialchars($stmt->error));
        }

    } catch (Exception $e) {
        $_SESSION['error_message'] = "Database error: " . htmlspecialchars($e->getMessage());
    }

    // Clean up
    if (isset($stmt)) { 
     	$stmt->close(); 
	}
	$connection->close();

	// Redirect back to manage catalog page
	header("Location: managecatalog.php");
	exit;
} else {
   // Redirect if the request is not POST
   header("Location: managecatalog.php");
   exit;
}
?>