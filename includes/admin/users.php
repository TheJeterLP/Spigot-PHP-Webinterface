<?php
require_once realpath(__DIR__ . '/../../functions.php');
requireRole('admin');

function getUsers(): array {
    $db = getDatabase();
    return $db->query("SELECT id, username, role, active, created_at, token FROM users ORDER BY id")->fetchAll();
}

$a = [
    'filename' => 'admin/users.php',
    'title' => 'Spigot - Users',
    'data' => getUsers()
];

return $a;
