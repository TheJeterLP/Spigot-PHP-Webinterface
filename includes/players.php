<?php

require_once realpath(__DIR__ . '/../functions.php');
requireLogin();

function getPlayers(): array {
    $json = getJsonFromPluginAPI('/players');

    if (!is_array($json)) {
        return [];
    }

    foreach ($json as &$player) {
        if (isset($player['uuid'])) {
            $player['uuid'] = str_replace('-', '', $player['uuid']);
        }
    }
    unset($player);
    return $json;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $players = getPlayers();
    echo json_encode([
        "online" => true,
        "count" => count($players),
        "players" => $players
    ]);

    exit();
} else {
    $a = [
        'filename' => 'players.php',
        'title' => 'Spigot - Players',
        'custom-js' => 'players.js',
        'custom-css' => 'players.css',
        'data' => getPlayers()
    ];
    return $a;
}