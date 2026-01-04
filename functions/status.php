<?php
require_once realpath(__DIR__ . '/../functions.php');
requireLogin();
header("Content-Type: application/json; charset=utf-8");

$data = getJsonFromPluginAPI('/online');

if (!$data) {
    echo json_encode([
        "online" => false,
        "status" => "Offline",
        "version" => "Unknown"
    ]);
    exit;
} else {
    echo json_encode([
        "online" => $data['online'],
        "status" => "Online",
        "version" => $data['version']
    ]);
}
