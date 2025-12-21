<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../includes/base.php';
requireLogin();
require_once __DIR__ . '/../classes/rcon.php';

header("Content-Type: application/json");

$rcon = new Rcon();

if (!$rcon->connect()) {
    echo json_encode([
        "online" => false,
        "status" => "Offline"
    ]);
    exit;
} else {
    echo json_encode([
        "online" => true,
        "status" => "Online"
    ]);
    $rcon->disconnect();
}


