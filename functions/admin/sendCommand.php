<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../../includes/base.php';
require_once __DIR__ . '/../../classes/rcon.php';
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

try {
    $rcon = new Rcon();
    if (!$rcon->connect()) {
        echo json_encode([
            "online" => false,
            "error" => "RCON nicht erreichbar"
        ]);
        exit;
    }

    $response = $rcon->command($cmd);
    echo htmlspecialchars($response);
    $rcon->disconnect();
} catch (Exception $e) {
    http_response_code(500);
    echo 'RCON Error! ' . htmlspecialchars($e->getMessage());
}
