<?php
require_once __DIR__ . '/../includes/base.php';
require_once __DIR__ . '/../config.php';
requireAnyRole(['admin', 'operator']);

$SPIGOT_SERVER_PATH = rtrim($SPIGOT_SERVER_PATH, '/');
$logfile = $SPIGOT_SERVER_PATH . '/logs/latest.log';

if (file_exists($logfile)) {
    $lines = file($logfile);
    $lastLines = array_slice($lines, -100); // letzte 100 Zeilen
    $lastLines = array_reverse($lastLines);
    echo htmlspecialchars(implode("", $lastLines));
} else {
    echo "Logfile nicht gefunden.";
}



