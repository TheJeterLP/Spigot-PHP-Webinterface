<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../../includes/base.php';
require_once __DIR__ . '/../../config.php';
requireRole('admin');
$cmd = trim($_POST['command'] ?? '');

if ($cmd === '') {
    http_response_code(400);
    exit('No Command Provided');
}


$blocked = ['stop', 'restart', 'op', 'deop'];

foreach ($blocked as $b) {
    if (preg_match('/^' . preg_quote($b, '/') . '\b/i', $cmd)) {
        http_response_code(403);
        exit('Command locked!');
    }
}

global $SPIGOT_PLUGIN_API_URL;

$url = $SPIGOT_PLUGIN_API_URL . "/command";
$payload = json_encode([
    "command" => $cmd
        ]);

$ctx = stream_context_create([
    "http" => [
        "method" => "POST",
        "timeout" => 3,
        "header" => [
            "Content-Type: application/json",
            "Accept: application/json",
            "X-API-Token: " . $_SESSION["api_token"]
        ],
        "content" => $payload
    ]
        ]);

$json = @file_get_contents($url, false, $ctx);
if ($json === false) {
    http_response_code(502);
    echo json_encode(["ok" => false, "error" => "plugin_unreachable"]);
    exit;
}

$data = json_decode($json, true);

if (!is_array($data)) {
    http_response_code(502);
    echo json_encode(["ok" => false, "error" => "invalid_plugin_response"]);
    exit;
}

echo json_encode($data);
exit;
