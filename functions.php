<?php

function showInfo($msg) {
    echo '<div class="container has-text-centered"><div id="is-info" class="notification is-info"><button class="delete"></button>' . $msg . '</div></div>';
}

function showError($msg) {
    echo '<div class="container has-text-centered"><div class="notification is-danger"><button class="delete"></button>' . $msg . '</div></div>';
}

function startMySession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.cookie_httponly', 1);
        //ini_set('session.cookie_secure', 1); // bei HTTPS
        ini_set('session.use_strict_mode', 1);
        session_start();
    }
}

function requireLogin(): void {
    startMySession();
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
    startMySession();
    return !empty($_SESSION['auth']);
}

function hasUserRole(string $role): bool {
    return isLoggedIn() && $_SESSION['role'] == $role;
}

function setupDb() {
    $db = getDatabase();
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

function getJsonFromPluginAPI($endpoint, $requestBody = null, $method = 'GET') {
    $config = require realpath(__DIR__ . '/config.php');
    $url = $config['spigot']['plugin-backend-url'] . $endpoint;
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

function getDatabase() {
    $dataFolder = realpath(__DIR__ . '/../data');
    if (!is_dir($dataFolder))
        mkdir($dataFolder, 0755, true);

    $db = new PDO('sqlite:' . $dataFolder . '/users.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $db;
}
