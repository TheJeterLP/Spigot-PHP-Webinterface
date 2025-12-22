<?php

require_once __DIR__ . '/../includes/base.php';
requireAnyRole(['admin', 'operator']);
require_once __DIR__ . '/../config.php';

function getPlugins(): array|false {
    global $SPIGOT_PLUGIN_API_PORT;
    $url = "http://127.0.0.1:" . (int) $SPIGOT_PLUGIN_API_PORT . "/plugins";
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

    $json = @file_get_contents($url, false, $ctx);

    if ($json === false) {
        return false;
    }

    $data = json_decode($json, true);

    if (!is_array($data)) {
        return false;
    }

    return $data;
}

header("Content-Type: application/json");

$plugins = getPlugins();

if ($plugins === false) {
    echo json_encode([
        "online" => false,
        "count" => 0,
        "plugins" => []
    ]);
} else {
    echo json_encode([
        "online" => true,
        "count" => count($plugins),
        "plugins" => $plugins
    ]);
}

