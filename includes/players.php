<?php

requireLogin();

$a = array();
$a['filename'] = 'players.php';
$a['title'] = 'Spigot - Players';
$a['data'] = array();
$a['custom-js'] = 'players.js';
$a['custom-css'] = 'players.css';

function getPlayers(): array|false {
    $json = getJsonFromPluginAPI('/players');

    if ($json === false) {
        return false;
    }

    $data = json_decode($json, true);

    foreach ($data as &$player) {
        if (isset($player['uuid'])) {
            $player['uuid'] = str_replace('-', '', $player['uuid']);
        }
    }
    unset($player);
    return $data;
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
    return $a;
}