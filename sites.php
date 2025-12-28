<?php

require_once 'functions.php';

$files = array();
$files['main'] = 'main.php';

if (isLoggedIn()) {
    $files['logout'] = 'user/logout.php';
    $files['players'] = 'players.php';
} else {
    $files['login'] = 'user/login.php';
}

if (hasAnyRole(['admin', 'operator'])) {
    $files['log'] = 'log.php';
    $files['plugins'] = 'plugins.php';
}

if (hasUserRole('admin')) {
    $files['admin/users'] = 'admin/users.php';
}

$files['notfound'] = 'errors/404.php';
$files['nopermission'] = 'errors/403.php';

