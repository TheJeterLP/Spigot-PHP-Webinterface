<?php
require_once realpath(__DIR__ . '/../functions.php');
requireAnyRole(['admin', 'operator']);

$config = require realpath(__DIR__ . '/../config.php');
echo shell_exec($config['spigot']['stop-command']);
?>