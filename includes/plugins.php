<?php

requireAnyRole(['admin', 'operator']);

$a = array();
$a['filename'] = 'plugins.php';
$a['title'] = 'Spigot - Plugins';
$a['data'] = array();
$a['custom-css'] = 'plugins.css';

function getPlugins(): array|false {
    $json = getJsonFromPluginAPI('/plugins');

    if ($json === false) {
        return false;
    }

    $data = json_decode($json, true);

    if (!is_array($data)) {
        return false;
    }

    return $data;
}

$plugins = getPlugins();

if ($plugins === false) {
    $a['data']['plugins'] = [];
} else {
    $a['data']['plugins'] = $plugins;
}

return $a;
