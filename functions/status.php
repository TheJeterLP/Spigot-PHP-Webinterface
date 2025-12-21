<?php
require_once __DIR__ . '/../includes/base.php';
require_once __DIR__ . '/../classes/rcon.php';
requireLogin();
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


