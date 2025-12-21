<?php
require_once __DIR__ . '/../../includes/base.php';
require_once __DIR__ . '/../../database/db.php';
requireRole('admin');

$action = $_POST['action'] ?? null;

switch ($action) {

case 'create':
    $stmt = $db->prepare(
        "INSERT INTO users (username, password, role)
         VALUES (?, ?, ?)"
    );
    $stmt->execute([
        $_POST['username'],
        password_hash($_POST['password'], PASSWORD_DEFAULT),
        $_POST['role']
    ]);
    break;

case 'delete':
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    break;

case 'toggle':
    $db->prepare(
        "UPDATE users SET active = NOT active WHERE id = ?"
    )->execute([$_POST['id']]);
    break;

case 'role':
    $db->prepare(
        "UPDATE users SET role = ? WHERE id = ?"
    )->execute([$_POST['role'], $_POST['id']]);
    break;
}

header('Location: /admin/users.php');
exit;