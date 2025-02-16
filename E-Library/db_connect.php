<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $connection = new mysqli('localhost', 'group2', 'group2', 'Library_web_db');
    $connection->set_charset("utf8mb4");
    // Check connection
    if ($connection->connect_error) {
        throw new Exception('Connection failed: ' . $connection->connect_error);
    }
} catch (mysqli_sql_exception $e) {
    die('Connection failed: ' . $e->getMessage());
}
?>
