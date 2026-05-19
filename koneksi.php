<?php
if (isset($GLOBALS['mysqli']) && $GLOBALS['mysqli'] instanceof mysqli) {
    $mysqli = $GLOBALS['mysqli'];
    return;
}

mysqli_report(MYSQLI_REPORT_OFF);

// Load .env
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            [$key, $value] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Koneksi
$host = $_ENV['DB_HOST'] ?? 'localhost';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? '';
$db   = $_ENV['DB_NAME'] ?? 'askep_new';

// Enhanced logging connections per minute
$logFile = __DIR__ . '/db_connection_log.txt';
$logTime = date('Y-m-d H:i'); // Log by minute
$clientIP = $_SERVER['REMOTE_ADDR'] ?? 'CLI'; // Get client IP or CLI if not available
$scriptName = $_SERVER['SCRIPT_NAME'] ?? 'unknown'; // Get the script name

if (file_exists($logFile)) {
    $logData = file_get_contents($logFile);
    $logEntries = explode("\n", trim($logData));
    $lastEntry = end($logEntries);

    if (strpos($lastEntry, "$logTime") === 0) {
        // Increment connection count for the current minute
        $parts = explode(' ', $lastEntry);
        $count = (int)$parts[2] + 1;
        $logEntries[count($logEntries) - 1] = "$logTime $clientIP $scriptName $count";
    } else {
        // New minute, start new log entry
        $logEntries[] = "$logTime $clientIP $scriptName 1";
    }

    file_put_contents($logFile, implode("\n", $logEntries));
} else {
    // Create log file with the first entry
    file_put_contents($logFile, "$logTime $clientIP $scriptName 1\n");
}

try {
    $mysqli = mysqli_connect($host, $user, $pass, $db);
    if (!$mysqli) {
        throw new Exception(mysqli_connect_error());
    }
} catch (Throwable $e) {
    error_log('DB connection failed: ' . $e->getMessage());
    if (!headers_sent()) {
        http_response_code(503);
    }
    echo 'Layanan sedang sibuk. Silakan coba lagi beberapa saat.';
    exit;
}

$GLOBALS['mysqli'] = $mysqli;