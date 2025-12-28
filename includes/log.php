<?php

requireAnyRole(['admin', 'operator']);

$a = array();
$a['filename'] = 'log.php';
$a['title'] = 'Spigot - Log';
$a['data'] = array();
$a['custom-js'] = 'log.js';

$SPIGOT_SERVER_PATH = rtrim($SPIGOT_SERVER_PATH, '/');
$logfile = $SPIGOT_SERVER_PATH . '/logs/latest.log';

if (file_exists($logfile)) {
    $lines = file($logfile);
    $lastLines = array_slice($lines, -100); // letzte 100 Zeilen
    $lastLines = array_reverse($lastLines);
    $a['data']['log'] = htmlspecialchars(implode("", $lastLines));
} else {
    $a['data']['log'] = "Logfile nicht gefunden.";
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo $a['data']['log'];
    exit();
} else {
    return $a;
}
