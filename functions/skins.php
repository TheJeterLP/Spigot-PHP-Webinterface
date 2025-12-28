<?php
require_once realpath(__DIR__ . '/../functions.php');
requireLogin();

// skins.php?mode=skins&uuid=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

$uuid = $_GET["uuid"] ?? "";
if (!preg_match('/^[a-f0-9]{32}$/i', $uuid)) {
    http_response_code(404);
    exit;
}

$mode = strtolower($_GET['mode'] ?? "");
if ($mode != 'skins' && $mode != 'capes' && $mode != 'heads') {
    http_response_code(404);
    exit;
}

$cacheDir = realpath(__DIR__ . '/../../cache/' . $mode . '/');

if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0755, true);
}

$cacheFile = $cacheDir . strtolower($uuid) . '.png';
$cacheTTL = 3600; // 1 Stunde
// Cache gültig?
if (file_exists($cacheFile) && time() - filemtime($cacheFile) < $cacheTTL) {
    header("Content-Type: image/png");
    header("Cache-Control: public, max-age=3600");
    readfile($cacheFile);
    exit;
}

if ($mode == 'capes') {
    $url = "https://api.capes.dev/load/$uuid/minecraft";
} else if ($mode == 'heads') {
    $url = "https://mc-heads.net/avatar/$uuid/64";
} else if ($mode == 'skins') {
    $url = "https://mc-heads.net/body/$uuid/left";
}

$ctx = stream_context_create([
    "http" => [
        "timeout" => 3,
        "header" => "User-Agent: Spigot-Panel"
    ]
        ]);

if ($mode == 'capes') {
    $jsonData = @file_get_contents($url, false, $ctx);
    if (!$jsonData) {
        exit;
    }
    $json = json_decode($jsonData, true);
    $capeUrl = $json["frontImageUrl"] ?? "";
    $data = @file_get_contents($capeUrl, false, $ctx);
} else {
    $data = @file_get_contents($url, false, $ctx);
}

if (!$data) {
    file_put_contents($cacheFile, "");
    http_response_code(204);
    exit;
}

file_put_contents($cacheFile, $data);
header("Content-Type: image/png");
header("Cache-Control: public, max-age=3600");
header("Content-Length: " . strlen($data));
echo $data;

