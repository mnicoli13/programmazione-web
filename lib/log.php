<?php
    function writeLog($message, $logFile = null) {
        print_r($message);
        exit;
        $logDir = __DIR__ . '/../logs'; // salva sempre in /logs accanto a init.php
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        if ($logFile === null) {
            $logFile = $logDir . '/app.log';
        }
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$timestamp] $message" . PHP_EOL;
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
?>