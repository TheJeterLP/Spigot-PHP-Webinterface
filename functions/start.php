<?php
require_once __DIR__ . '/../includes/base.php';
require_once __DIR__ . '/../config.php';
requireAnyRole(['admin', 'operator']);
echo shell_exec($SPIGOT_START_COMMAND);
?>