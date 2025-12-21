<?php
require_once __DIR__ . '/../includes/base.php';
requireLogin();

// cape.php?uuid=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

$uuid = $_GET["uuid"] ?? "";
if (!preg_match('/^[a-f0-9]{32}$/i', $uuid)) {
    http_response_code(404);
    exit;
}


$cacheDir  = __DIR__ . '/../cache/capes/';
$cacheFile = $cacheDir . strtolower($uuid) . '.png';
$cacheTTL  = 3600; // 1 Stunde
// Cache gültig?
if (file_exists($cacheFile) && time() - filemtime($cacheFile) < $cacheTTL) {
    header("Content-Type: image/png");
    header("Cache-Control: public, max-age=3600");
    readfile($cacheFile);
    exit;
}

// Cape von Crafatar holen
$url = "https://crafatar.com/capes/$uuid";
$ctx = stream_context_create([
    "http" => [
        "timeout" => 3,
        "header"  => "User-Agent: Spigot-Panel\r\n"
    ]
]);

$data = @file_get_contents($url, false, $ctx);

// Kein Cape vorhanden → leere Antwort cachen
if (!$data) {
    file_put_contents($cacheFile, "");
    http_response_code(204);
    exit;
}

// Speichern
file_put_contents($cacheFile, $data);

// Ausgeben
header("Content-Type: image/png");
header("Cache-Control: public, max-age=3600");
echo $data;
