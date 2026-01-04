<?php
require_once realpath(__DIR__ . '/../../functions.php');
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

$payload = json_encode(["command" => $cmd]);
$json = getJsonFromPluginAPI('/command', $payload, 'POST');
if ($json === false) {
    http_response_code(502);
    echo json_encode(["ok" => false, "error" => "plugin_unreachable"]);
    exit;
}

if (!is_array($json)) {
    http_response_code(502);
    echo json_encode(["ok" => false, "error" => "invalid_plugin_response"]);
    exit;
}

echo json_encode($json);
exit;
