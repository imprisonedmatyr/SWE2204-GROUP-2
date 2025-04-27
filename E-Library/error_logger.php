<?php
// error_logger.php

function log_error($errorMessage, $errorSeverity = 'Medium', $affectedPage = 'unknown') {
    $logFile = __DIR__ . '/error_log.txt';
    $timestamp = date('Y-m-d H:i:s');

    $entry = "[$timestamp] Severity: $errorSeverity | Page: $affectedPage | Error: $errorMessage\n";
    
    file_put_contents($logFile, $entry, FILE_APPEND);
}
?>
