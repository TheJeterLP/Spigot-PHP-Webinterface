<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$db = new PDO('sqlite:' . __DIR__ . '/../data/users.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$stmt = $db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT 'viewer',
    active INTEGER NOT NULL DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    token TEXT
);");

// Insert a default admin user if not exists
$users = $db->query(
    "SELECT id FROM users WHERE username = 'admin'"
)->fetchAll();

if (count($users) === 0) {
    $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (username, password, role, token) VALUES (:username, :password, :role, :token)");
    $stmt->execute([
        ':username' => 'admin',
        ':password' => $hashedPassword,
        ':role' => 'admin',
        ':token' => bin2hex(random_bytes(32))
    ]);
}
