<?php

require_once __DIR__ . '/../includes/base.php';
require_once __DIR__ . '/../config.php';
requireLogin();

header("Content-Type: application/json; charset=utf-8");

function isOnline(): bool {
    global $SPIGOT_PLUGIN_API_PORT;
    $url = "http://127.0.0.1:" . (int) $SPIGOT_PLUGIN_API_PORT . "/online";
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
    return $data['online'];
}

if (!isOnline()) {
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
}
