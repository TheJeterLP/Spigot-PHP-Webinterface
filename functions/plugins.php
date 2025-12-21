<?php
require_once __DIR__ . '/../includes/base.php';
requireAnyRole(['admin', 'operator']);
require_once __DIR__ . '/../classes/rcon.php';

function parsePluginsWithStatus(string $input): array
{
    // Alles vor "):" abschneiden
    if (!preg_match('/\):\s*(.+)$/', $input, $matches)) {
        return [];
    }

    $pluginPart = $matches[1];

    // Plugins anhand von Komma trennen
    $rawPlugins = array_map('trim', explode(',', $pluginPart));

    $result = [];

    foreach ($rawPlugins as $plugin) {

        // führenden Farbecode erfassen (§a, §c, ...)
        if (preg_match('/^(§[0-9a-fk-or])(.+)$/i', $plugin, $m)) {
            $colorCode = $m[1];
            $namePart  = $m[2];
        } else {
            $colorCode = null;
            $namePart  = $plugin;
        }

        // Reset-Codes (§f) aus dem Namen entfernen
        $name = preg_replace('/§[0-9a-fk-or]/i', '', $namePart);

        // Status bestimmen
        $active = match (strtolower($colorCode)) {
            '§a' => true,
            '§c' => false,
            default => null // unbekannt / nicht markiert
        };

        $result[] = [
            'name'      => $name,
            'active'    => $active,
            'color'     => $colorCode,
            'raw'       => $plugin
        ];
    }

    return $result;
}


header("Content-Type: application/json");

$rcon = new Rcon();

if (!$rcon->connect()) {
    echo json_encode([
        "online" => false,
        "error" => "RCON nicht erreichbar"
    ]);
    exit;
}

$response = $rcon->command("bukkit:plugins");
$rcon->disconnect();
$plugins = parsePluginsWithStatus($response);

echo json_encode([
    "online" => true,
    "count" => count($plugins),
    "plugins" => $plugins
]);
