<?php
//Used to setup the inital folders

$startFolder = realpath(__DIR__ . '/../') . DIRECTORY_SEPARATOR;
$foldersToCreate = ['data', 'cache', 'cache/skins', 'cache/heads', 'cache/capes'];

foreach($foldersToCreate as $folder) {
    $fullPath = $startFolder . $folder;
    if(!is_dir($fullPath)) mkdir($fullPath, 0755, true);
}


session_start();

function requireLogin(): void {
    if (empty($_SESSION['auth'])) {
        header('Location: /login');
        exit;
    }
}

function requireRole(string $role): void {
    requireLogin();

    if ($_SESSION['role'] !== $role) {
        http_response_code(403);
        die('No permission');
    }
}

function requireAnyRole(array $roles): void {
    requireLogin();

    if (!in_array($_SESSION['role'], $roles, true)) {
        http_response_code(403);
        die('No Permission');
    }
}

function hasAnyRole(array $roles): bool {
    return isLoggedIn() && in_array($_SESSION['role'], $roles, true);
}


function isLoggedIn(): bool {
    return !empty($_SESSION['auth']);
}

function hasUserRole(string $role): bool {
    return isLoggedIn() && $_SESSION['role'] == $role;
}
?>
