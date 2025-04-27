<?php
    $tests = [
        'test_addbook.php',
    ];
    
    $results = [];
    
    foreach ($tests as $test_file) {
        require_once "test_cases/$test_file";
        $function_name = str_replace('.php', '', $test_file);
        if (function_exists($function_name)) {
            $result = $function_name();
            $results[] = $result;
            echo "[{$result['timestamp']}] {$result['name']}: {$result['status']}<br>";
        }
    }
    
    // Save to log
    if (!file_exists("../logs")) {
        mkdir("../logs", 0777, true);
    }
    $logfile = fopen("../logs/test_log.csv", "a");
    foreach ($results as $r) {
        fputcsv($logfile, [$r['timestamp'], $r['name'], $r['status']]);
    }
    fclose($logfile);
    

?>