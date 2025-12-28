<?php

require_once realpath(__DIR__ . '/../functions.php');
requireAnyRole(['admin', 'operator']);

function getLog(): string {
    $config = require realpath(__DIR__ . '/../config.php');
    $SPIGOT_SERVER_PATH = rtrim($config['spigot']['server-path'], '/');
    $logfile = $SPIGOT_SERVER_PATH . '/logs/latest.log';

    if (file_exists($logfile)) {
        $lines = file($logfile);
        $lastLines = array_slice($lines, -100); // letzte 100 Zeilen
        $lastLines = array_reverse($lastLines);
        return htmlspecialchars(implode("", $lastLines));
    } else {
        return "Logfile nicht gefunden.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo getLog();
    exit();
} else {
    $a = [
        'filename' => 'log.php',
        'title' => 'Spigot - Log',
        'data' => getLog(),
        'custom-js' => 'log.js'
    ];
    return $a;
}
