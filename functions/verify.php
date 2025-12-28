<?php
declare(strict_types=1);

ini_set('display_errors', 1);
error_reporting(1);

header("Content-Type: application/json");

// Token aus Header
$token = filter_input(INPUT_SERVER, 'HTTP_X_API_TOKEN', FILTER_UNSAFE_RAW);
$token = is_string($token) ? trim($token) : '';

if (!$token) {
    http_response_code(401);
    echo json_encode(["ok" => false]);
    exit;
}

$db = new PDO('sqlite:' . __DIR__ . '/../../data/users.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$stmt = $db->prepare(
    "SELECT username, role FROM users WHERE token = :token AND active = 1"
);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["ok" => false, "error" => "prepare_failed"]);
    exit;
}

if (!$stmt->execute([':token' => $token])) {
    http_response_code(500);
    echo json_encode(["ok" => false, "error" => "execute_failed"]);
    exit;
}

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(403);
    echo json_encode(["ok" => false]);
    exit;
}

echo json_encode([
    "ok" => true,
    "user" => $user["username"],
    "role" => $user["role"]
]);


