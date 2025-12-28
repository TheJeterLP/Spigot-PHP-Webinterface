<?php
require_once __DIR__ . '/../../functions.php';

requireRole('admin');

$a = array();
$a['filename'] = 'admin/users.php';
$a['title'] = 'Spigot - Users';
$a['data'] = array();

$db = new PDO('sqlite:' . __DIR__ . '/../../data/users.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$a['data']['users'] = $db->query("SELECT id, username, role, active, created_at, token FROM users ORDER BY id")->fetchAll();

return $a;