<?php

$a = array();
$a['filename'] = 'user/login.php';
$a['title'] = 'Login';
$a['data'] = array();
$a['header-footer'] = false;
$a['custom-css'] = 'login.css';
$a['body-class'] = 'd-flex align-items-center py-4 bg-body-tertiary';
$a['main-class'] = 'form-signin w-100 m-auto';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['login_attempts'] ??= 0;

    if ($_SESSION['login_attempts'] > 5) {
        die('Too many failed login attempts.');
    }

    $_SESSION['login_attempts']++;

    $stmt = $db->prepare(
            'SELECT id, username, password, role, token
         FROM users 
         WHERE username = :u AND active = 1'
    );
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

