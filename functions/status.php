<?php
require_once __DIR__ . '/../functions.php';
requireLogin();
header("Content-Type: application/json; charset=utf-8");

function getData(): array|false {
    $json = getJsonFromPluginAPI('/online');
    if ($json === false) {
        return false;
    }
    $data = json_decode($json, true);
    
    if (!is_array($data)) {
        return false;
    }
    
    return $data;
}

$data = getData();

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
