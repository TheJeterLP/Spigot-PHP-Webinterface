<?php
require_once realpath(__DIR__ . '/../../functions.php');

$a = [
    'filename'  => 'user/login.php',
    'title'     => 'Login',
    'data'      => [],
    'header-footer' => false,
    'custom-css' => 'login.css',
    'body-class' => 'd-flex align-items-center py-4 bg-body-tertiary',
    'main-class' => 'form-signin w-100 m-auto'
];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['login_attempts'] ??= 0;

    if ($_SESSION['login_attempts'] > 5) {
        die('Too many failed login attempts.');
    }

    $_SESSION['login_attempts']++;
    
    $db = getDatabase();
    $stmt = $db->prepare('SELECT id, username, password, role, token FROM users WHERE username = :u AND active = 1');
    $stmt->execute(['u' => $_POST['username']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        session_regenerate_id(true);

        $_SESSION['auth'] = true;
        $_SESSION['uid'] = $user['id'];
        $_SESSION['user'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['api_token'] = $user['token'];

        header('Location: /');
        exit;
    }

    $a['data']['error'] = 'Login failed.';
}

return $a;

