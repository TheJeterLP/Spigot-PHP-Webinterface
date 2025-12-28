<?php

require_once realpath(__DIR__ . '/../functions.php');
requireAnyRole(['admin', 'operator']);

function getPlugins(): array {
    $json = getJsonFromPluginAPI('/plugins');

    if ($json === false) {
        return [];
    }

    $data = json_decode($json, true);

    if (!is_array($data)) {
        return [];
    }

    return $data;
}

$a = [
    'filename' => 'plugins.php',
    'title' => 'Spigot - Plugins',
    'data' => getPlugins(),
    'custom-css' => 'plugins.css'
];

return $a;
