<?php

require_once realpath(__DIR__ . '/../functions.php');
requireAnyRole(['admin', 'operator']);

$a = [
    'filename' => 'plugins.php',
    'title' => 'Spigot - Plugins',
    'data' => getJsonFromPluginAPI('/plugins'),
    'custom-css' => 'plugins.css'
];

return $a;
