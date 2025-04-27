<?php
// log_analyzer.php

$heartbeatLog = __DIR__ . '/heartbeat_log.txt';
$errorLog = __DIR__ . '/error_log.txt';

// Read heartbeat log
$heartbeatLines = file_exists($heartbeatLog) ? file($heartbeatLog, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
$totalChecks = count($heartbeatLines);

// Count system OK vs FAIL (assuming you wrote "System Status: OK" or "System Status: FAIL")
$okCount = 0;
$failCount = 0;

foreach ($heartbeatLines as $line) {
    if (strpos($line, 'OK') !== false) {
        $okCount++;
    } elseif (strpos($line, 'FAIL') !== false) {
        $failCount++;
    }
}

// Read error log
$errorLines = file_exists($errorLog) ? file($errorLog, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
$totalErrors = count($errorLines);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Reliability Report</title>
</head>
<body>
    <h1>System Reliability Report</h1>
    
    <h2>Heartbeat Summary</h2>
    <p>Total Checks: <strong><?php echo $totalChecks; ?></strong></p>
    <p>OK Responses: <strong><?php echo $okCount; ?></strong></p>
    <p>FAIL Responses: <strong><?php echo $failCount; ?></strong></p>
    <p>System Uptime Rate: 
        <strong>
            <?php 
            echo $totalChecks > 0 ? round(($okCount / $totalChecks) * 100, 2) : 0;
            ?>%
        </strong>
    </p>

    <h2>Error Summary</h2>
    <p>Total Errors Logged: <strong><?php echo $totalErrors; ?></strong></p>
    
    <h3>Recent Errors</h3>
    <ul>
        <?php
        foreach (array_slice(array_reverse($errorLines), 0, 10) as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        ?>
    </ul>

</body>
</html>
