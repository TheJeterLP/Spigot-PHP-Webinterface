<?php
require_once __DIR__ . '/../../functions.php';
requireRole('admin');

$action = $_POST['action'] ?? null;
$db = new PDO('sqlite:' . __DIR__ . '/../../data/users.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


switch ($action) {

case 'create':
    $stmt = $db->prepare(
        "INSERT INTO users (username, password, role, token)
         VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([
        $_POST['username'],
        password_hash($_POST['password'], PASSWORD_DEFAULT),
        $_POST['role'],
        bin2hex(random_bytes(32))
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
case 'resettoken':
    $db->prepare(
        "UPDATE users SET token = ? WHERE id = ?"
    )->execute([bin2hex(random_bytes(32)), $_POST['id']]);
    break;
}

header('Location: /admin/users');
exit;