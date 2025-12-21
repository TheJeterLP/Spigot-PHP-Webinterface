<?php
require_once __DIR__ . '/../includes/base.php';
requireLogin();
require_once __DIR__ . '/../classes/rcon.php';

function getUUID(string $name): ?string
{
    $cacheFile = __DIR__ . "/uuid_cache.json";
    $cache = file_exists($cacheFile)
        ? json_decode(file_get_contents($cacheFile), true)
        : [];

    if (isset($cache[$name])) {
        return $cache[$name];
    } else {
        $uuid = getUUIDFromMojang($name);
        if ($uuid) {
            $cache[$name] = $uuid;
            file_put_contents($cacheFile, json_encode($cache));
        }
        return $uuid;
    }
}

function getUUIDFromMojang(string $player): ?string
{
    $url = "https://api.mojang.com/users/profiles/minecraft/" . urlencode($player);

    $ctx = stream_context_create([
        "http" => [
            "timeout" => 3,
            "header" => "User-Agent: Spigot-Panel\r\n"
        ]
    ]);

    $json = @file_get_contents($url, false, $ctx);
    if (!$json) return null;

    $data = json_decode($json, true);
    return $data["id"] ?? null;
}

header("Content-Type: application/json");

$rcon = new Rcon();

if (!$rcon->connect()) {
    echo json_encode([
        "online" => false,
        "error" => "RCON nicht erreichbar"
    ]);
    exit;
}

$response = $rcon->command("minecraft:list");
$rcon->disconnect();

/*
 Beispiel:
 There are 2 of a max of 20 players online: Steve, Alex
*/

$players = [];

if (preg_match('/online:\s*(.*)$/', $response, $m)) {
    if (trim($m[1]) !== "") {
        foreach (explode(",", $m[1]) as $name) {
            $name = trim($name);
            $uuid = getUUID($name);

            $players[] = [
                "name" => $name,
                "uuid" => $uuid
            ];
        }
    }
}

echo json_encode([
    "online" => true,
    "count" => count($players),
    "players" => $players
]);
