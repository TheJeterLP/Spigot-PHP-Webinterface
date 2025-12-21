<?php
require_once __DIR__ . '/../includes/base.php';
require_once __DIR__ . '/../config.php';
requireRole('admin');


// Output initialisieren
$output = shell_exec($SPIGOT_START_COMMAND);
echo $output;
?>