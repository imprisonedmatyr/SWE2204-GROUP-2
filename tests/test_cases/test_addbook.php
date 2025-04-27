<?php
    function test_add_book() {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        // Simulate POST values
        $_POST['title'] = "Test Book No Files";
        $_POST['author'] = "Author Test";
        $_POST['category'] = "Test Category";
        $_POST['genre'] = "Test Genre";
        $_POST['year'] = 2025;

        // Faking files but with minimum acceptable values
        $_FILES['summary'] = [
            'name' => 'summary.pdf',
            'type' => 'application/pdf',
            'tmp_name' => '',     
            'error' => UPLOAD_ERR_NO_FILE,
            'size' => 0
        ];

        $_FILES['book_cover'] = [
            'name' => 'cover.jpg',
            'type' => 'image/jpeg',
            'tmp_name' => '',     
            'error' => UPLOAD_ERR_NO_FILE,
            'size' => 0
        ];

        // Start output buffer to suppress redirects/echoes
        ob_start();
        include "add_book.php";
        ob_end_clean();

        // Check result
        if (isset($_SESSION['error_message'])) {
            $status = 'pass';  
        } else {
            $status = 'fail'; 
        }

        return [
            'name' => 'Add Book Without File Uploads',
            'status' => $status,
            'timestamp' => date("Y-m-d H:i:s")
        ];
    }

?>