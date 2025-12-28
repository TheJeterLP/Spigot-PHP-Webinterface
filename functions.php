<?php

require_once __DIR__ . '/config.php';

function showInfo($msg) {
    echo '<div class="container has-text-centered"><div id="is-info" class="notification is-info"><button class="delete"></button>' . $msg . '</div></div>';
}

function showError($msg) {
    echo '<div class="container has-text-centered"><div class="notification is-danger"><button class="delete"></button>' . $msg . '</div></div>';
}

function requireLogin(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['auth']) || empty($_SESSION['api_token'])) {
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
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return !empty($_SESSION['auth']);
}

function hasUserRole(string $role): bool {
    return isLoggedIn() && $_SESSION['role'] == $role;
}

function setupDb($db) {
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
}

function createRequiredFolders() {
    $startFolder = realpath(__DIR__ . '/../') . DIRECTORY_SEPARATOR;
    $foldersToCreate = ['data'];

    foreach ($foldersToCreate as $folder) {
        $fullPath = $startFolder . $folder;
        if (!is_dir($fullPath))
            mkdir($fullPath, 0755, true);
    }
}

function getJsonFromPluginAPI($endpoint, $requestBody = null, $method = 'GET') {
    global $SPIGOT_PLUGIN_API_URL;
    $url = $SPIGOT_PLUGIN_API_URL . $endpoint;
    $ctx = stream_context_create([
        "http" => [
            "method" => $method,
            "timeout" => 3,
            "header" => [
                "X-API-Token: " . $_SESSION["api_token"],
                "Accept: application/json"
            ],
            "content" => $requestBody
        ]
    ]);

    $json = @file_get_contents($url, false, $ctx);

    if ($json === false) {
        return false;
    }

    $data = json_decode($json, true);

    if (!is_array($data)) {
        return false;
    }

    return json_encode($data);
}
