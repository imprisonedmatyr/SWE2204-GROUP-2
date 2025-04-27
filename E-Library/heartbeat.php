<?php
// heartbeat.php

$logFile = __DIR__ . '/heartbeat_log.txt';

// Get current timestamp
$timestamp = date('Y-m-d H:i:s');

// Simple check (you can add more tests here, e.g., DB connection)
$status = "OK";

// Write to the heartbeat log
file_put_contents($logFile, "$timestamp - System Status: $status\n", FILE_APPEND);
?>
