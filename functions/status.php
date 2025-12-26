<?php

require_once __DIR__ . '/../includes/base.php';
require_once __DIR__ . '/../config.php';
requireLogin();

header("Content-Type: application/json; charset=utf-8");

function getData(): array|false {
    global $SPIGOT_PLUGIN_API_URL;
    $url = $SPIGOT_PLUGIN_API_URL . "/online";
    $ctx = stream_context_create([
        "http" => [
            "method" => "GET",
            "timeout" => 3,
            "header" => [
                "X-API-Token: " . $_SESSION["api_token"],
                "Accept: application/json"
            ]
        ]
    ]);
    
    $json = file_get_contents($url, false, $ctx);
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
