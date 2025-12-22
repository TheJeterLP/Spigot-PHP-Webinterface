<?php

require_once __DIR__ . '/../includes/base.php';
require_once __DIR__ . '/../config.php';
requireLogin();

function getPlayers(): array|false {
    global $SPIGOT_PLUGIN_API_PORT;
    $url = "http://127.0.0.1:" . (int) $SPIGOT_PLUGIN_API_PORT . "/players";
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
    
     foreach ($data as &$player) {
        if (isset($player['uuid'])) {
            $player['uuid'] = str_replace('-', '', $player['uuid']);
        }
    }
    unset($player);
    return $data;
}

header("Content-Type: application/json");

$players = getPlayers();

echo json_encode([
    "online" => true,
    "count" => count($players),
    "players" => $players
]);
