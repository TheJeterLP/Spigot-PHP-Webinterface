<?php
require_once __DIR__ . '/../includes/base.php';
require_once __DIR__ . '/../config.php';
requireRole('admin');

$SPIGOT_SERVER_PATH = rtrim($SPIGOT_SERVER_PATH, '/');
$logfile = $SPIGOT_SERVER_PATH . '/logs/latest.log';

if (file_exists($logfile)) {
    $lines = file($logfile);
    $filtered = array_filter($lines, fn($line) =>
        !str_contains($line, 'RCON')
    );

    $lastLines = array_slice($filtered, -100); // letzte 100 Zeilen
    $lastLines = array_reverse($lastLines);
    echo htmlspecialchars(implode("", $lastLines));
} else {
    echo "Logfile nicht gefunden.";
}



